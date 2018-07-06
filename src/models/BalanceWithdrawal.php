<?php

namespace yuncms\balance\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yuncms\behaviors\JsonBehavior;
use yuncms\sms\jobs\NoticeJob;
use yuncms\user\models\User;
use yuncms\validators\JsonValidator;

/**
 * This is the model class for table "{{%balance_withdrawals}}".
 * 余额提现明细表
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $status
 * @property string $amount
 * @property string $channel
 * @property string $metadata
 * @property string $extra
 * @property integer $created_at
 * @property integer $canceled_at
 * @property integer $succeeded_at
 *
 * @property User $user
 */
class BalanceWithdrawal extends ActiveRecord
{
    const STATUS_CREATED = 0b0;//已申请： created
    const STATUS_PENDING = 0b1;//处理中： pending
    const STATUS_SUCCEEDED = 0b10;//完成： succeeded
    const STATUS_FAILED = 0b11;//失败： failed
    const STATUS_CANCELED = 0b100;//取消： canceled

    //事件定义
    const BEFORE_SUCCEEDED = 'beforeSucceeded';
    const AFTER_SUCCEEDED = 'afterSucceeded';

    const BEFORE_FAILED = 'beforeFailed';
    const AFTER_FAILED = 'afterReFailed';

    const BEFORE_CANCELED = 'beforeCanceled';
    const AFTER_CANCELED = 'afterReCanceled';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%balance_withdrawals}}';
    }

    /**
     * 定义行为
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::class,
            'attributes' => [
                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
            ],
        ];
        $behaviors['configuration'] = [
            'class' => JsonBehavior::class,
            'attributes' => ['metadata', 'extra'],
        ];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount', 'channel'], 'required'],
            [['amount'], 'number'],
            [['metadata', 'extra'], 'string'],
            [['channel'], 'string', 'max' => 64],
            ['amount', 'balanceValidate'],

            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],

            [['metadata', 'extra'], JsonValidator::class],
            // status rule
            ['status', 'default', 'value' => self::STATUS_CREATED],
            ['status', 'in', 'range' => [self::STATUS_CREATED, self::STATUS_PENDING, self::STATUS_SUCCEEDED, self::STATUS_FAILED, self::STATUS_CANCELED]],];
    }

    /**
     * Validate balance
     */
    public function balanceValidate()
    {
        if (bcsub($this->user->balance, $this->amount) < 0) {
            $message = Yii::t('yuncms/balance', 'Insufficient balance.');
            $this->addError('amount', $message);
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yuncms/balance', 'ID'),
            'user_id' => Yii::t('yuncms/balance', 'User Id'),
            'status' => Yii::t('yuncms/balance', 'Status'),
            'amount' => Yii::t('yuncms/balance', 'Amount'),
            'channel' => Yii::t('yuncms/balance', 'Channel'),
            'metadata' => Yii::t('yuncms/balance', 'Metadata'),
            'extra' => Yii::t('yuncms/balance', 'Extra'),
            'created_at' => Yii::t('yuncms/balance', 'Created At'),
            'canceled_at' => Yii::t('yuncms/balance', 'Canceled At'),
            'succeeded_at' => Yii::t('yuncms/balance', 'Succeeded At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return BalanceWithdrawalQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BalanceWithdrawalQuery(get_called_class());
    }

    /**
     * 设置提现完成
     * @return bool
     */
    public function setSucceeded()
    {
        $this->trigger(self::BEFORE_SUCCEEDED);
        $succeeded = (bool)$this->updateAttributes(['status' => static::STATUS_SUCCEEDED, 'succeeded_at' => time()]);
        $this->trigger(self::AFTER_SUCCEEDED);

        Yii::$app->queue->push(new NoticeJob([
            'mobile' => $this->user->mobile,
            'template' => Yii::$app->params['sms.balance.withdrawal.succeeded'] ?? 259720,
            'data' => [$this->amount],
        ]));
        return $succeeded;
    }

    /**
     * 设置提现失败
     * @return bool
     */
    public function setFailed()
    {
        $this->trigger(self::BEFORE_FAILED);
        $succeeded = (bool)$this->updateAttributes(['status' => static::STATUS_FAILED]);
        if ($succeeded) {
            $balance = bcadd($this->user->balance, $this->amount);
            if (($transaction = BalanceTransaction::create([
                'user_id' => $this->user_id,
                'type' => BalanceTransaction::TYPE__WITHDRAWAL_FAILED,
                'description' => Yii::t('yuncms/balance', 'Withdrawal Failed'),
                'source' => $this->id,
                'amount' => $this->amount,
                'balance' => $balance,
            ]))) {
                $this->user->updateAttributes(['balance' => $balance]);
                Yii::$app->queue->push(new NoticeJob([
                    'mobile' => $this->user->mobile,
                    'template' => Yii::$app->params['sms.balance.withdrawal.failed'] ?? 259720,
                    'data' => [$this->amount],
                ]));
            }
        }
        $this->trigger(self::AFTER_FAILED);
        return $succeeded;
    }

    /**
     * 设置提现取消
     * @return bool
     */
    public function setCanceled()
    {
        $this->trigger(self::BEFORE_CANCELED);
        $succeeded = (bool)$this->updateAttributes(['status' => static::STATUS_CANCELED, 'canceled_at' => time()]);
        if ($succeeded) {
            $balance = bcadd($this->user->balance, $this->amount);
            if (($transaction = BalanceTransaction::create([
                'user_id' => $this->user_id,
                'type' => BalanceTransaction::TYPE__WITHDRAWAL_REVOKED,
                'description' => Yii::t('yuncms/balance', 'Withdrawal Revoked'),
                'source' => $this->id,
                'amount' => $this->amount,
                'balance' => $balance,
            ]))) {
                $this->user->updateAttributes(['balance' => $balance]);

                Yii::$app->queue->push(new NoticeJob([
                    'mobile' => $this->user->mobile,
                    'template' => Yii::$app->params['sms.balance.withdrawal.canceled'] ?? 259720,
                    'data' => [$this->amount],
                ]));
            }
        }
        $this->trigger(self::AFTER_CANCELED);
        return $succeeded;
    }

    /**
     * 保存前先扣钱
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($insert) {
            $balance = bcsub($this->user->balance, $this->amount);
            if (($transaction = BalanceTransaction::create([
                'user_id' => $this->user_id,
                'type' => BalanceTransaction::TYPE_WITHDRAWAL,
                'description' => Yii::t('yuncms/balance', 'Withdrawal'),
                'source' => $this->id,
                'amount' => $this->amount,
                'balance' => $balance,
            ]))) {
                $this->user->updateAttributes(['balance' => $balance]);

                Yii::$app->queue->push(new NoticeJob([
                    'mobile' => $this->user->mobile,
                    'template' => Yii::$app->params['sms.balance.withdrawal.created'] ?? 259720,
                    'data' => [$this->amount],
                ]));
            }
        }

        return true;
    }
}

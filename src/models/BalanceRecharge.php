<?php

namespace yuncms\balance\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yuncms\db\ActiveRecord;
use yuncms\transaction\models\TransactionCharge;
use yuncms\transaction\models\TransactionRefund;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%balance_recharges}}".
 * 余额充值
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $succeeded
 * @property integer $refunded
 * @property string $amount
 * @property string $channel
 * @property string $user_fee
 * @property string $charge_id
 * @property string $balance_bonus_id
 * @property string $balance_transaction_id
 * @property string $description
 * @property string $metadata
 * @property integer $created_at
 * @property integer $succeeded_at
 *
 * @property User $user
 * @property BalanceTransaction $balanceTransaction 钱包明细对象
 * @property TransactionCharge $charge 支付对象
 */
class BalanceRecharge extends ActiveRecord
{

    //场景定义
    const SCENARIO_CREATE = 'create';//创建
    const SCENARIO_UPDATE = 'update';//更新

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%balance_recharges}}';
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
        $behaviors['user'] = [
            'class' => BlameableBehavior::class,
            'attributes' => [
                ActiveRecord::EVENT_BEFORE_INSERT => ['user_id']
            ],
        ];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['channel', 'required'],
            [['user_id', 'balance_transaction_id'], 'integer'],
            [['amount', 'user_fee', 'balance_bonus_id'], 'number'],
            [['metadata'], 'string'],
            [['succeeded', 'refunded'], 'string', 'max' => 1],
            [['charge_id'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
            [['succeeded', 'refunded'], 'boolean'],
            ['succeeded', 'default', 'value' => false],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['balance_transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => BalanceTransaction::class, 'targetAttribute' => ['balance_transaction_id' => 'id']],
            [['charge_id'], 'exist', 'skipOnError' => true, 'targetClass' => TransactionCharge::class, 'targetAttribute' => ['charge_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yuncms/balance', 'ID'),
            'user_id' => Yii::t('yuncms/balance', 'User Id'),
            'succeeded' => Yii::t('yuncms/balance', 'Succeeded'),
            'refunded' => Yii::t('yuncms/balance', 'Refunded'),
            'amount' => Yii::t('yuncms/balance', 'Amount'),
            'user_fee' => Yii::t('yuncms/balance', 'User Fee'),
            'charge_id' => Yii::t('yuncms/balance', 'Charge ID'),
            'balance_bonus_id' => Yii::t('yuncms/balance', 'Balance Bonus ID'),
            'balance_transaction_id' => Yii::t('yuncms/balance', 'Balance Transaction ID'),
            'description' => Yii::t('yuncms/balance', 'Description'),
            'metadata' => Yii::t('yuncms/balance', 'Metadata'),
            'created_at' => Yii::t('yuncms/balance', 'Created At'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getRefunds()
    {
        return $this->hasMany(TransactionRefund::class, ['charge_order_no' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBalanceTransaction()
    {
        return $this->hasOne(BalanceTransaction::class, ['id' => 'balance_transaction_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCharge()
    {
        return $this->hasOne(TransactionCharge::class, ['id' => 'charge_id']);
    }

    /**
     * @inheritdoc
     * @return BalanceRechargeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BalanceRechargeQuery(get_called_class());
    }

    /**
     * 是否是作者
     * @return bool
     */
    public function getIsAuthor()
    {
        return $this->user_id == Yii::$app->user->id;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $charge = new TransactionCharge([
                'order_no' => (string)$this->id,
                'order_class' => self::class,
                'amount' => $this->amount,
                'user_id' => $this->user_id,
                'channel' => $this->channel,
                'currency' => 'CNY',
                'subject' => '余额充值',
                'body' => '余额充值' . $this->amount . '元',
            ]);
            if ($charge->save()) {
                $this->link('charge', $charge);
            }
        }
    }

    /**
     * 订单支付回调
     * @param string $orderNo
     * @param string $chargeId
     * @param array $params
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function setPaid($orderNo, $chargeId, $params)
    {
        if (($model = static::findOne(['id' => $orderNo, 'charge_id' => $chargeId])) != null) {
            if ($model->succeeded) {
                return true;
            }
            if (Balance::increase($model->user, $model->amount, BalanceTransaction::TYPE_RECHARGE, 'recharge')) {
                return $model->updateAttributes(['succeeded' => true, 'succeeded_at' => time()]);
            }
            return false;
        } else {
            return false;
        }
    }

    /**
     * 设置订单退款成功
     * @param string $orderNo 订单号
     * @param string $chargeId 支付号
     * @param array $params 附加参数
     * @return bool
     */
    public static function setRefunded($orderNo, $chargeId, $params)
    {
        return false;
    }
}

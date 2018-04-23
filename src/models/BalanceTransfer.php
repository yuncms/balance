<?php

namespace yuncms\balance\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yuncms\helpers\ArrayHelper;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%balance_transfer}}".
 * 余额转账
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $recipient_id
 * @property integer $status
 * @property string $amount
 * @property string $order_no
 * @property string $user_fee
 * @property string $user_balance_transaction_id
 * @property string $recipient_balance_transaction
 * @property string $description
 * @property string $metadata
 * @property integer $created_at
 *
 * @property User $user
 *
 * @property-read boolean $isAuthor 是否是作者
 * @property-read boolean $isDraft 是否草稿
 * @property-read boolean $isPublished 是否发布
 */
class BalanceTransfer extends ActiveRecord
{
    const STATUS_SUCCEEDED = 0b0; //成功
    const STATUS_FAILURE = 0b1;//失败

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%balance_transfer}}';
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
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'recipient_id', 'amount'], 'required'],
            [['user_id', 'recipient_id', 'user_balance_transaction_id', 'recipient_balance_transaction'], 'integer'],
            [['amount', 'user_fee'], 'number'],
            [['metadata'], 'string'],
            [['order_no'], 'string', 'max' => 64],
            [['description'], 'string', 'max' => 60],
            [['user_id'], 'balanceValidate'],
            [['recipient_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            // status rule
            ['status', 'default', 'value' => self::STATUS_SUCCEEDED],
            ['status', 'in', 'range' => [self::STATUS_SUCCEEDED, self::STATUS_FAILURE]],];
    }

    /**
     * 可转账余额验证
     */
    public function balanceValidate()
    {
        if (($user = User::findOne(['id' => $this->user_id])) != null) {
            if ($user->withdrawable_balance < $this->amount) {
                $message = Yii::t('yuncms/balance', 'Exceeded the maximum transfer amount.');
                $this->addError('className', $message);
            }
        } else {
            $message = Yii::t('yuncms/balance', "Unknown User '{user}'", ['user' => $this->user_id]);
            $this->addError('className', $message);
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
            'recipient_id' => Yii::t('yuncms/balance', 'Recipient ID'),
            'status' => Yii::t('yuncms/balance', 'Status'),
            'amount' => Yii::t('yuncms/balance', 'Amount'),
            'order_no' => Yii::t('yuncms/balance', 'Order No'),
            'user_fee' => Yii::t('yuncms/balance', 'User Fee'),
            'user_balance_transaction_id' => Yii::t('yuncms/balance', 'User Balance Transaction ID'),
            'recipient_balance_transaction' => Yii::t('yuncms/balance', 'Recipient Balance Transaction'),
            'description' => Yii::t('yuncms/balance', 'Description'),
            'metadata' => Yii::t('yuncms/balance', 'Metadata'),
            'created_at' => Yii::t('yuncms/balance', 'Created At'),
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
     * @return BalanceTransferQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BalanceTransferQuery(get_called_class());
    }

    /**
     * 保存前
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        //从源用户余额扣钱
        $withdrawableBalance = bcsub($this->user->withdrawable_balance, $this->amount);
        if (($balanceTransaction = BalanceTransaction::create([
            'user_id' => $this->user_id,
            'type' => BalanceTransaction::TYPE_TRANSFER,
            'description' => $this->description,
            'source' => $this->id,
            'amount' => $this->amount,
            'available_balance' => $withdrawableBalance
        ]))) {
            $this->user->updateAttributes(['withdrawable_balance' => $withdrawableBalance]);
            $this->updateAttributes(['paid' => true, 'time_paid' => time(), 'balance_transaction_id' => $balanceTransaction->id]);
        }

        // ...custom code here...
        return true;
    }

    /**
     * @inheritdoc
     */
//    public function afterSave($insert, $changedAttributes)
//    {
//        parent::afterSave($insert, $changedAttributes);
//        Yii::$app->queue->push(new ScanTextJob([
//            'modelId' => $this->getPrimaryKey(),
//            'modelClass' => get_class($this),
//            'scenario' => $this->isNewRecord ? 'new' : 'edit',
//            'category'=>'',
//        ]));
//        // ...custom code here...
//    }
}

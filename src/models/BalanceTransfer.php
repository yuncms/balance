<?php

namespace yuncms\balance\models;

use Yii;
use yuncms\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
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
 * @property string $recipient_balance_transaction_id
 * @property string $description
 * @property string $metadata
 * @property integer $created_at
 *
 * @property User $user
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
            [['user_id', 'recipient_id', 'user_balance_transaction_id', 'recipient_balance_transaction_id'], 'integer'],
            [['amount', 'user_fee'], 'number'],
            [['metadata'], 'string'],
            [['order_no'], 'string', 'max' => 64],
            [['description'], 'string', 'max' => 60],

            [['recipient_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],

            // status rule
            ['status', 'default', 'value' => self::STATUS_SUCCEEDED],
            ['status', 'in', 'range' => [self::STATUS_SUCCEEDED, self::STATUS_FAILURE]],];
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
            'recipient_balance_transaction_id' => Yii::t('yuncms/balance', 'Recipient Balance Transaction ID'),
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
}

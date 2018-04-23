<?php

namespace yuncms\transaction\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%transaction_balance_settlement}}".
 *
 * 余额结算 当一笔订单设定了余额结算信息参数时，支付完成后，系统将自动将扣除手续费（user_fee）后的支付金额结算到指定的用户余额账户并生成 balance_settlement 对象。
 * 通常使用该对象查询一笔或多笔订单余额结算的状态。注意： 结算的入账状态是系统处理的一个中间状态，一般不需要关心。
 *
 * @property integer $id 用户余额结算对象 ID
 * @property integer $user_id 结算的  user 对象的  id 。
 * @property string $amount 结算金额，包含用户手续费。
 * @property string $user_fee 向结算用户收取的手续费。
 * @property integer $refunded 余额结算金额是否有退款。
 * @property string $amount_refunded 已退款的余额结算金额。
 * @property string $charge_id 结算关联的  charge 对象的  id 。
 * @property string $charge_order_no 结算关联的  charge 对象内的  order_no 。
 * @property string $charge_transaction_no 结算关联的  charge 对象内的  transaction_no 。
 * @property string $failure_msg 结算失败信息。
 * @property integer $credited_at 入账完成时间。
 * @property integer $succeeded_at 结算完成时间。
 * @property integer $created_at 创建时间，用 Unix 时间戳表示。
 * @property integer $status 结算状态
 *
 * @property User $user
 */
class TransactionBalanceSettlement extends ActiveRecord
{

    const STATUS_CREATED = 0b0;//待结算： created （表示余额还未到结算用户余额账户）
    const STATUS_CREDITED = 0b1;//已入账： credited （表示余额已到账但不可用）
    const STATUS_SUCCEEDED = 0b10;//已结算： succeeded （表示余额到账且可用）

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transaction_balance_settlement}}';
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
     * 是否已经入账
     * @return bool
     */
    public function isCredited()
    {
        return $this->status == self::STATUS_CREDITED;
    }

    /**
     * 是否已经结算
     * @return bool
     */
    public function isSucceeded()
    {
        return $this->status == self::STATUS_SUCCEEDED;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'amount', 'charge_id'], 'required'],
            [['user_id', 'created_at'], 'integer'],
            [['amount', 'user_fee', 'amount_refunded'], 'number'],
            [['refunded'], 'string', 'max' => 1],
            [['charge_id'], 'string', 'max' => 50],
            [['charge_order_no', 'charge_transaction_no'], 'string', 'max' => 64],
            [['failure_msg'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['charge_id'], 'exist', 'skipOnError' => true, 'targetClass' => TransactionCharge::class, 'targetAttribute' => ['charge_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yuncms/transaction', 'ID'),
            'user_id' => Yii::t('yuncms/transaction', 'User Id'),
            'amount' => Yii::t('yuncms/transaction', 'Amount'),
            'user_fee' => Yii::t('yuncms/transaction', 'User Fee'),
            'refunded' => Yii::t('yuncms/transaction', 'Refunded'),
            'amount_refunded' => Yii::t('yuncms/transaction', 'Amount Refunded'),
            'charge_id' => Yii::t('yuncms/transaction', 'Charge ID'),
            'charge_order_no' => Yii::t('yuncms/transaction', 'Charge Order No'),
            'charge_transaction_no' => Yii::t('yuncms/transaction', 'Charge Transaction No'),
            'failure_msg' => Yii::t('yuncms/transaction', 'Failure Msg'),
            'created_at' => Yii::t('yuncms/transaction', 'Created At'),
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
     * @return TransactionBalanceSettlementQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionBalanceSettlementQuery(get_called_class());
    }
}

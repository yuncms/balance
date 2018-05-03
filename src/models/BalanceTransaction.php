<?php

namespace yuncms\balance\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yuncms\user\models\User;
use yuncms\db\ActiveRecord;

/**
 * This is the model class for table "{{%balance_transaction}}".
 *
 * 余额变动历史
 *
 * @property string $id 流水号
 * @property integer $user_id 对应 User 对象的  id
 * @property integer $amount 订单总金额（必须大于 0）
 * @property string $balance 该笔交易发生后，用户的可用余额。
 * @property string $description 描述
 * @property string $source 关联对象的 ID
 * @property string $type 交易类型
 * @property integer $created_at 时间
 *
 * @property User $user
 * @property BalanceRecharge[] $recharges
 */
class BalanceTransaction extends ActiveRecord
{
    const TYPE_RECHARGE = 'recharge';//充值
    const TYPE_RECHARGE_REFUND = 'recharge_refund';//充值退款
    const TYPE_RECHARGE_REFUND_FAILED = 'recharge_refund_failed';//充值退款失败
    const TYPE_WITHDRAWAL = 'withdrawal';//提现申请
    const TYPE__WITHDRAWAL_FAILED = 'withdrawal_failed';//提现失败
    const TYPE__WITHDRAWAL_REVOKED = 'withdrawal_revoked';//提现撤销
    const TYPE_PAYMENT = 'payment';//支付/收款
    const TYPE_PAYMENT_REFUND = 'payment_refund';//退款/收到退款
    const TYPE_TRANSFER = 'transfer';//转账/收到转账
    const TYPE_RECEIPTS_EXTRA = 'receipts_extra';//赠送
    const TYPE_ROYALTY = 'royalty';//分润/收到分润
    const TYPE_CREDITED = 'credited';//入账
    const TYPE_CREDITED_REFUND = 'credited_refund';//入账退款
    const TYPE_CREDITED_REFUND_FAILED = 'credited_refund_failed';//入账退款失败

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%balance_transaction}}';
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
            [['user_id', 'amount', 'source', 'type'], 'required'],
            [['user_id', 'source'], 'integer'],
            [['balance', 'amount',], 'number'],
            [['description'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 30],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'amount' => Yii::t('yuncms/balance', 'Amount'),
            'balance' => Yii::t('yuncms/balance', 'Balance'),
            'description' => Yii::t('yuncms/balance', 'Description'),
            'source' => Yii::t('yuncms/balance', 'Source Id'),
            'type' => Yii::t('yuncms/balance', 'Transaction Type'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getRecharges()
    {
        return $this->hasMany(BalanceRecharge::class, ['balance_transaction_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return BalanceTransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BalanceTransactionQuery(get_called_class());
    }
}

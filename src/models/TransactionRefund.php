<?php

namespace yuncms\transaction\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;
use yuncms\behaviors\JsonBehavior;
use yuncms\db\ActiveRecord;
use yuncms\helpers\ArrayHelper;
use yuncms\helpers\Json;
use yuncms\validators\JsonValidator;

/**
 * This is the model class for table "{{%transaction_refunds}}".
 *
 * @property int $id
 * @property int $amount
 * @property int $succeed
 * @property string $status
 * @property int $time_succeed
 * @property string $description
 * @property string $failure_code
 * @property string $failure_msg
 * @property int $charge_id
 * @property string $charge_order_no
 * @property string $transaction_no
 * @property string $funding_source
 * @property int $created_at
 *
 * @property TransactionCharge $charge
 */
class TransactionRefund extends ActiveRecord
{
    //退款成功触发
    const EVENT_AFTER_SUCCEEDED = 'refund.succeeded';

    //退款状态
    const STATUS_PENDING = 0b0;
    const STATUS_SUCCEEDED = 0b1;
    const STATUS_FAILED = 0b10;

    //退款资金来源
    const FUNDING_SOURCE_UNSETTLED = 'unsettled_funds';//使用未结算资金退款
    const FUNDING_SOURCE_RECHARGE = 'recharge_funds';//使用可用余额退款

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%transaction_refunds}}';
    }

    /**
     * 定义行为
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return ArrayHelper::merge($behaviors, [
            'id' => [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'id',
                ],
                'value' => function ($event) {
                    return $event->sender->generateId();
                }
            ],
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ],
            ],
            'jsonAttributes' => [
                'class' => JsonBehavior::class,
                'attributes' => ['extra', 'metadata'],
            ],
            'user' => [
                'class' => BlameableBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'user_id',
                ],
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount', 'description', 'charge_id'], 'required'],
            [['amount', 'charge_id'], 'integer'],

            [['description'], 'string', 'max' => 255],

            //退款资金来源
            [['funding_source'], 'string'],
            ['funding_source', 'default', 'value' => self::FUNDING_SOURCE_UNSETTLED],
            ['funding_source', 'in', 'range' => [self::FUNDING_SOURCE_UNSETTLED, self::FUNDING_SOURCE_RECHARGE]],

            //付款单检测
            [['charge_id'], 'chargeValidate'],


            //附加信息
            [['metadata', 'extra'], JsonValidator::class],

            //退款是否成功
            ['succeed', 'boolean'],
            ['succeed', 'default', 'value' => false],

            //退款状态
            ['status', 'default', 'value' => self::STATUS_PENDING],
            ['status', 'in', 'range' => [self::STATUS_PENDING, self::STATUS_SUCCEEDED, self::STATUS_FAILED]],
        ];
    }

    /**
     * Validate charge
     */
    public function chargeValidate()
    {
        if (($charge = TransactionCharge::findOne(['id' => $this->charge_id])) != null) {
            if (bcsub($charge->amount, $charge->amount_refunded) < $this->amount) {
                $message = Yii::t('yuncms/transaction', 'Exceeded the maximum refund amount.');
                $this->addError('className', $message);
            } else {
                $this->charge_order_no = $charge->order_no;
            }
        } else {
            $message = Yii::t('yuncms/transaction', "Unknown charge '{charge}'", ['charge' => $this->charge_id]);
            $this->addError('className', $message);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yuncms', 'ID'),
            'amount' => Yii::t('yuncms/transaction', 'Amount'),
            'succeed' => Yii::t('yuncms/transaction', 'Succeed'),
            'status' => Yii::t('yuncms/transaction', 'Status'),
            'time_succeed' => Yii::t('yuncms/transaction', 'Time Succeed'),
            'description' => Yii::t('yuncms/transaction', 'Description'),
            'failure_code' => Yii::t('yuncms/transaction', 'Failure Code'),
            'failure_msg' => Yii::t('yuncms/transaction', 'Failure Msg'),
            'charge_id' => Yii::t('yuncms/transaction', 'Charge ID'),
            'charge_order_no' => Yii::t('yuncms/transaction', 'Charge Order No'),
            'transaction_no' => Yii::t('yuncms/transaction', 'Transaction No'),
            'funding_source' => Yii::t('yuncms/transaction', 'Funding Source'),
            'created_at' => Yii::t('yuncms', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCharge()
    {
        return $this->hasOne(TransactionCharge::class, ['id' => 'charge_id']);
    }

    /**
     * 设置退款错误
     * @param string $code
     * @param string $msg
     * @return bool
     */
    public function setFailure($code, $msg)
    {
        return (bool)$this->updateAttributes(['status' => self::STATUS_FAILED, 'failure_code' => $code, 'failure_msg' => $msg]);
    }

    /**
     * 设置退款凭证
     * @param string $transactionNo 支付渠道返回的交易流水号。
     * @param array $extra 退款凭证
     * @return bool
     */
    public function setRefund($transactionNo, $extra)
    {
        return (bool)$this->updateAttributes(['transaction_no' => $transactionNo, 'extra' => Json::encode($extra)]);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\UnknownClassException
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $this->charge->updateAttributes(['refunded' => true]);
            $this->charge->getChannelObject()->refund($this);
        }
    }

    /**
     * {@inheritdoc}
     * @return TransactionRefundQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionRefundQuery(get_called_class());
    }

    /**
     * 生成交易流水号
     * @return string
     */
    protected function generateId()
    {
        $i = rand(0, 9999);
        do {
            if (9999 == $i) {
                $i = 0;
            }
            $i++;
            $id = time() . str_pad($i, 4, '0', STR_PAD_LEFT);
            $row = (new Query())->from(static::tableName())->where(['id' => $id])->exists();
        } while ($row);
        return $id;
    }
}

<?php

namespace yuncms\transaction\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\UnknownClassException;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;
use yuncms\base\JsonObject;
use yuncms\behaviors\IpBehavior;
use yuncms\behaviors\JsonBehavior;
use yuncms\db\ActiveRecord;
use yuncms\helpers\ArrayHelper;
use yuncms\helpers\Json;
use yuncms\transaction\contracts\ChannelInterface;
use yuncms\user\models\User;
use yuncms\validators\JsonValidator;

/**
 * This is the model class for table "{{%transaction_charges}}".
 *
 * @property int $id
 * @property int $paid
 * @property int $refunded
 * @property int $reversed
 * @property string $channel
 * @property string $order_no
 * @property string $client_ip
 * @property int $amount
 * @property int $amount_settle
 * @property string $currency
 * @property string $subject
 * @property string $body
 * @property JsonObject $extra
 * @property int $time_paid
 * @property int $time_expire
 * @property int $time_settle
 * @property string $transaction_no
 * @property int $amount_refunded
 * @property string $failure_code
 * @property string $failure_msg
 * @property JsonObject $metadata
 * @property string $description
 * @property array $credential
 * @property int $created_at
 *
 *
 * @property-read string outTradeNo
 *
 * @property TransactionRefund[] $refunds
 */
class TransactionCharge extends ActiveRecord
{
    //支付成功触发事件
    const EVENT_AFTER_SUCCEEDED = 'charge.succeeded';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%transaction_charges}}';
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
            'client_id' => [
                'class' => IpBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['client_ip']
                ],
            ],
            'jsonAttributes' => [
                'class' => JsonBehavior::class,
                'attributes' => ['extra', 'credential', 'metadata'],
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paid', 'refunded', 'reversed',], 'boolean'],
            [['paid', 'refunded', 'reversed',], 'default', 'value' => false],

            [['amount', 'time_paid', 'amount_refunded'], 'integer'],
            [['order_no', 'amount', 'currency', 'subject', 'body'], 'required'],
            [['metadata'], 'string'],
            [['channel'], 'string', 'max' => 50],
            [['order_no', 'failure_code', 'failure_msg', 'description'], 'string', 'max' => 255],
            [['client_ip'], 'string', 'max' => 45],
            [['currency'], 'string', 'max' => 3],
            [['subject'], 'string', 'max' => 32],
            [['body'], 'string', 'max' => 128],
            [['channel'], 'channelExists'],
            ['amount_refunded', 'default', 'value' => 0],
            [['transaction_no'], 'string', 'max' => 64],

            //支付凭证
            [['credential', 'metadata', 'extra'], JsonValidator::class],

            //最晚付款时间
            [['time_expire'], 'integer', 'min' => time() + 900],
            [['time_expire'], 'default', 'value' => time() + 86400],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yuncms', 'ID'),
            'paid' => Yii::t('yuncms/transaction', 'Paid'),
            'refunded' => Yii::t('yuncms/transaction', 'Refunded'),
            'reversed' => Yii::t('yuncms/transaction', 'Reversed'),
            'channel' => Yii::t('yuncms/transaction', 'Channel'),
            'order_no' => Yii::t('yuncms/transaction', 'Order No'),
            'client_ip' => Yii::t('yuncms/transaction', 'Client Ip'),
            'amount' => Yii::t('yuncms/transaction', 'Amount'),
            'amount_settle' => Yii::t('yuncms/transaction', 'Amount Settle'),
            'currency' => Yii::t('yuncms/transaction', 'Currency'),
            'subject' => Yii::t('yuncms/transaction', 'Subject'),
            'body' => Yii::t('yuncms/transaction', 'Body'),
            'time_paid' => Yii::t('yuncms/transaction', 'Time Paid'),
            'time_expire' => Yii::t('yuncms/transaction', 'Time Expire'),
            'time_settle' => Yii::t('yuncms/transaction', 'Time Settle'),
            'transaction_no' => Yii::t('yuncms/transaction', 'Transaction No'),
            'amount_refunded' => Yii::t('yuncms/transaction', 'Amount Refunded'),
            'failure_code' => Yii::t('yuncms/transaction', 'Failure Code'),
            'failure_msg' => Yii::t('yuncms/transaction', 'Failure Msg'),
            'metadata' => Yii::t('yuncms/transaction', 'Metadata'),
            'description' => Yii::t('yuncms/transaction', 'Description'),
            'created_at' => Yii::t('yuncms', 'Created At'),
        ];
    }

    /**
     * Validate channel exists
     */
    public function channelExists()
    {
        try {
            TransactionChannel::getChannelByIdentity($this->channel);
        } catch (InvalidConfigException $e) {
            $this->addError('channel', $e->getMessage());
        } catch (UnknownClassException $e) {
            $this->addError('channel', $e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     * @return TransactionChargeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionChargeQuery(get_called_class());
    }

    /**
     * 商户订单号
     * @return int
     */
    public function getOutTradeNo()
    {
        return $this->id;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * 获取退款
     * @return \yii\db\ActiveQuery
     */
    public function getRefunds()
    {
        return $this->hasMany(TransactionRefund::class, ['charge_id' => 'id']);
    }

    /**
     * 设置已付款状态
     * @param string $transactionNo 支付渠道返回的交易流水号。
     * @return bool
     */
    public function setPaid($transactionNo)
    {
        $paid = (bool)$this->updateAttributes(['transaction_no' => $transactionNo, 'time_paid' => time(), 'paid' => true]);
        $this->trigger(self::EVENT_AFTER_SUCCEEDED);
        return $paid;
    }

    /**
     * 设置交易凭证
     * @param string $transactionNo 支付渠道返回的交易流水号。
     * @param array $credential 支付凭证
     * @return bool
     */
    public function setTransactionCredential($transactionNo, $credential)
    {
        return (bool)$this->updateAttributes(['transaction_no' => $transactionNo, 'credential' => Json::encode($credential)]);
    }

    /**
     * 设置支付错误
     * @param string $code
     * @param string $msg
     * @return bool
     */
    public function setFailure($code, $msg)
    {
        return (bool)$this->updateAttributes(['failure_code' => $code, 'failure_msg' => $msg]);
    }

    /**
     * 设置订单状态以撤销
     * @return bool
     */
    public function setReversed()
    {
        return (bool)$this->updateAttributes(['reversed' => true, 'credential' => null]);
    }

    /**
     * 关闭支付
     * @return TransactionCharge
     * @throws InvalidConfigException
     * @throws UnknownClassException
     */
    public function setClose()
    {
        return $this->getChannelObject()->close($this);
    }

    /**
     * 查询渠道
     * @return TransactionCharge
     * @throws InvalidConfigException
     * @throws UnknownClassException
     */
    public function queryChannel()
    {
        return $this->getChannelObject()->query($this);
    }

    /**
     * 获取渠道对象
     * @return ChannelInterface
     * @throws InvalidConfigException
     * @throws UnknownClassException
     */
    public function getChannelObject()
    {
        return TransactionChannel::getChannelByIdentity($this->channel);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        //保存完成以后去支付平台下单
        if ($insert) {
            try {
                $channel = TransactionChannel::getChannelByIdentity($this->channel);
                $channel->charge($this);
            } catch (InvalidConfigException $e) {

            } catch (UnknownClassException $e) {
            }
        }
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

<?php

namespace yuncms\transaction\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yuncms\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%transaction_transfer}}".
 * 企业付款表，处理提现
 *
 * @property integer $id
 * @property string $type
 * @property string $channel
 * @property integer $status
 * @property string $order_no
 * @property string $amount
 * @property string $currency
 * @property string $recipient
 * @property string $description
 * @property string $transaction_no
 * @property string $failure_msg
 * @property string $metadata
 * @property string $extra
 * @property integer $created_at
 * @property integer $transferred_at
 *
 */
class TransactionTransfer extends ActiveRecord
{

    //付款状态
    const STATUS_SCHEDULED = 0b0;//scheduled: 待发送
    const STATUS_PENDING = 0b1;//pending: 处理中
    const STATUS_PAID = 0b10;//paid: 付款成功
    const STATUS_FAILED = 0b11;//failed: 付款失败

    //事件定义
    const BEFORE_PUBLISHED = 'beforePublished';
    const AFTER_PUBLISHED = 'afterPublished';
    const BEFORE_REJECTED = 'beforeRejected';
    const AFTER_REJECTED = 'afterRejected';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transaction_transfer}}';
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
            [['type', 'channel', 'order_no', 'amount', 'currency', 'recipient', 'description'], 'required'],
            [['amount'], 'number'],
            [['metadata', 'extra'], 'string'],
            [['type'], 'string', 'max' => 5],
            [['channel', 'order_no', 'transaction_no'], 'string', 'max' => 64],
            [['currency'], 'string', 'max' => 3],
            [['recipient', 'description', 'failure_msg'], 'string', 'max' => 255],

            // status rule
            ['status', 'default', 'value' => self::STATUS_SCHEDULED],
            ['status', 'in', 'range' => [self::STATUS_SCHEDULED, self::STATUS_PENDING, self::STATUS_PAID, self::STATUS_FAILED]]];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yuncms/transaction', 'ID'),
            'type' => Yii::t('yuncms/transaction', 'Type'),
            'channel' => Yii::t('yuncms/transaction', 'Channel Identity'),
            'status' => Yii::t('yuncms/transaction', 'Status'),
            'order_no' => Yii::t('yuncms/transaction', 'Order No'),
            'amount' => Yii::t('yuncms/transaction', 'Amount'),
            'currency' => Yii::t('yuncms/transaction', 'Currency'),
            'recipient' => Yii::t('yuncms/transaction', 'Recipient'),
            'description' => Yii::t('yuncms/transaction', 'Description'),
            'transaction_no' => Yii::t('yuncms/transaction', 'Transaction No'),
            'failure_msg' => Yii::t('yuncms/transaction', 'Failure Msg'),
            'metadata' => Yii::t('yuncms/transaction', 'Metadata'),
            'extra' => Yii::t('yuncms/transaction', 'Extra'),
            'created_at' => Yii::t('yuncms/transaction', 'Created At'),
            'transferred_at' => Yii::t('yuncms/transaction', 'Transferred At'),
        ];
    }

    /**
     * @inheritdoc
     * @return TransactionTransferQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionTransferQuery(get_called_class());
    }

    /**
     * 审核通过
     * @return int
     */
    public function setPublished()
    {
        $this->trigger(self::BEFORE_PUBLISHED);
        $rows = $this->updateAttributes(['status' => static::STATUS_PUBLISHED, 'published_at' => time()]);
        $this->trigger(self::AFTER_PUBLISHED);
        return $rows;
    }

    /**
     * 拒绝通过
     * @param string $failedReason 拒绝原因
     * @return int
     */
    public function setRejected($failedReason)
    {
        $this->trigger(self::BEFORE_REJECTED);
        $rows = $this->updateAttributes(['status' => static::STATUS_REJECTED, 'failed_reason' => $failedReason]);
        $this->trigger(self::AFTER_REJECTED);
        return $rows;
    }
}

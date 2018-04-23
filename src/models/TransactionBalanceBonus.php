<?php

namespace yuncms\transaction\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yuncms\helpers\ArrayHelper;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%transaction_balance_bonus}}".
 *
 * 余额赠送
 *
 * @property string $id
 * @property boolean $paid
 * @property integer $user_id
 * @property string $amount
 * @property string $order_no
 * @property string $description
 * @property string $metadata
 * @property integer $balance_transaction_id
 * @property integer $time_paid
 * @property integer $created_at
 *
 * @property User $user
 */
class TransactionBalanceBonus extends ActiveRecord
{
    //场景定义
    const SCENARIO_CREATE = 'create';//创建

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transaction_balance_bonus}}';
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
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return ArrayHelper::merge($scenarios, [
            static::SCENARIO_CREATE => ['amount', 'user_id', 'order_no'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'amount', 'order_no'], 'required'],
            [['user_id', 'balance_transaction_id'], 'integer'],
            ['paid', 'boolean'],
            ['paid', 'default', 'value' => false],
            [['amount'], 'number', 'min' => 0.01],
            [['order_no'], 'string', 'max' => 64],
            [['description'], 'string', 'max' => 60],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'order_no' => Yii::t('yuncms/transaction', 'Order No'),
            'description' => Yii::t('yuncms/transaction', 'Description'),
            'metadata' => Yii::t('yuncms/transaction', 'Metadata'),
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
     * 关联余额变动历史
     * @return \yii\db\ActiveQuery
     */
    public function getBalanceTransaction()
    {
        return $this->hasOne(TransactionBalanceTransaction::class, ['id' => 'balance_transaction_id']);
    }

    /**
     * @inheritdoc
     * @return TransactionBalanceBonusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionBalanceBonusQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {//保存后开始赠送余额
            $balance = bcadd($this->user->balance, $this->amount);
            if (($transaction = TransactionBalanceTransaction::create([
                'user_id' => $this->user_id,
                'type' => TransactionBalanceTransaction::TYPE_RECEIPTS_EXTRA,
                'description' => $this->description,
                'source' => $this->id,
                'amount' => $this->amount,
                'balance' => $balance,
            ]))) {
                $this->user->updateAttributes(['balance' => $balance]);
                $this->updateAttributes(['paid' => true, 'time_paid' => time(), 'balance_transaction_id' => $transaction->id]);
            }
        }
    }
}

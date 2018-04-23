<?php

namespace yuncms\balance\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yuncms\db\ActiveRecord;
use yuncms\helpers\ArrayHelper;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%balance_bonus}}".
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
class BalanceBonus extends ActiveRecord
{
    //场景定义
    const SCENARIO_CREATE = 'create';//创建

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%balance_bonus}}';
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
            'id' => Yii::t('yuncms/balance', 'ID'),
            'user_id' => Yii::t('yuncms/balance', 'User Id'),
            'amount' => Yii::t('yuncms/balance', 'Amount'),
            'order_no' => Yii::t('yuncms/balance', 'Order No'),
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
     * 关联余额变动历史
     * @return \yii\db\ActiveQuery
     */
    public function getBalanceTransaction()
    {
        return $this->hasOne(BalanceTransaction::class, ['id' => 'balance_transaction_id']);
    }

    /**
     * @inheritdoc
     * @return BalanceBonusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BalanceBonusQuery(get_called_class());
    }

    /**
     * @inheritdoc
     * @throws \yii\db\Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert
            && ($transactionId = Balance::increase($this->user, $this->amount, BalanceTransaction::TYPE_RECEIPTS_EXTRA, $this->description, $this->id)) != false) {//保存后开始赠送余额
            $this->updateAttributes(['paid' => true, 'time_paid' => time(), 'balance_transaction_id' => $transactionId]);
        }
    }
}

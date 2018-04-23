<?php

namespace yuncms\balance\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yuncms\db\ActiveRecord;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%balance_recharges}}".
 * 充值模型
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $user_fee
 * @property string $balance_bonus
 * @property string $balance_transaction_id
 * @property string $description
 * @property string $metadata
 * @property integer $created_at
 *
 * @property User $user
 * @property BalanceTransaction $balanceTransaction
 */
class BalanceRecharge extends ActiveRecord
{
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
            [['user_id'], 'required'],
            [['user_id', 'balance_transaction_id'], 'integer'],
            [['user_fee', 'balance_bonus'], 'number'],
            [['metadata'], 'string'],
            [['description'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['balance_transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => BalanceTransaction::class, 'targetAttribute' => ['balance_transaction_id' => 'id']],
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
            'user_fee' => Yii::t('yuncms/balance', 'User Fee'),
            'balance_bonus' => Yii::t('yuncms/balance', 'Balance Bonus'),
            'balance_transaction_id' => Yii::t('yuncms/balance', 'Balance Transaction ID'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getBalanceTransaction()
    {
        return $this->hasOne(BalanceTransaction::class, ['id' => 'balance_transaction_id']);
    }

    /**
     * @inheritdoc
     * @return BalanceRechargeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BalanceRechargeQuery(get_called_class());
    }
}

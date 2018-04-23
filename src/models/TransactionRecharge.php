<?php

namespace yuncms\transaction\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yuncms\helpers\ArrayHelper;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%transaction_recharges}}".
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
 * @property TransactionBalanceTransaction $balanceTransaction
 *
 * @property-read boolean $isAuthor 是否是作者
 */
class TransactionRecharge extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transaction_recharges}}';
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
            [['balance_transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => TransactionBalanceTransaction::class, 'targetAttribute' => ['balance_transaction_id' => 'id']],
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
            'user_fee' => Yii::t('yuncms/transaction', 'User Fee'),
            'balance_bonus' => Yii::t('yuncms/transaction', 'Balance Bonus'),
            'balance_transaction_id' => Yii::t('yuncms/transaction', 'Balance Transaction ID'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getBalanceTransaction()
    {
        return $this->hasOne(TransactionBalanceTransaction::class, ['id' => 'balance_transaction_id']);
    }

    /**
     * @inheritdoc
     * @return TransactionRechargeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionRechargeQuery(get_called_class());
    }

//    public function afterFind()
//    {
//        parent::afterFind();
//        // ...custom code here...
//    }

    /**
     * @inheritdoc
     */
//    public function beforeSave($insert)
//    {
//        if (!parent::beforeSave($insert)) {
//            return false;
//        }
//
//        // ...custom code here...
//        return true;
//    }

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

    /**
     * @inheritdoc
     */
//    public function beforeDelete()
//    {
//        if (!parent::beforeDelete()) {
//            return false;
//        }
//        // ...custom code here...
//        return true;
//    }

    /**
     * @inheritdoc
     */
//    public function afterDelete()
//    {
//        parent::afterDelete();
//
//        // ...custom code here...
//    }
}

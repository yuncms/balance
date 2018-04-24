<?php

namespace yuncms\balance\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yuncms\db\ActiveRecord;
use yuncms\helpers\ArrayHelper;
use yuncms\transaction\models\TransactionCharge;
use yuncms\transaction\models\TransactionRefund;
use yuncms\user\models\User;

/**
 * This is the model class for table "{{%balance_recharges}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $succeeded
 * @property integer $refunded
 * @property string $amount
 * @property string $user_fee
 * @property string $charge_id
 * @property string $balance_bonus_id
 * @property string $balance_transaction_id
 * @property string $description
 * @property string $metadata
 * @property integer $created_at
 * @property integer $succeeded_at
 *
 * @property User $user
 * @property BalanceTransaction $balanceTransaction 钱包明细对象
 * @property TransactionCharge $charge 支付对象
 */
class BalanceRecharge extends ActiveRecord
{

    //场景定义
    const SCENARIO_CREATE = 'create';//创建
    const SCENARIO_UPDATE = 'update';//更新

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
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return ArrayHelper::merge($scenarios, [
            static::SCENARIO_CREATE => [],
            static::SCENARIO_UPDATE => [],
        ]);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'charge_id'], 'required'],
            [['user_id', 'balance_transaction_id', 'created_at', 'succeeded_at'], 'integer'],
            [['amount', 'user_fee', 'balance_bonus_id'], 'number'],
            [['metadata'], 'string'],
            [['succeeded', 'refunded'], 'string', 'max' => 1],
            [['charge_id'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['balance_transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => BalanceTransaction::class, 'targetAttribute' => ['balance_transaction_id' => 'id']],
            [['charge_id'], 'exist', 'skipOnError' => true, 'targetClass' => TransactionCharge::class, 'targetAttribute' => ['charge_id' => 'id']],
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
            'succeeded' => Yii::t('yuncms/balance', 'Succeeded'),
            'refunded' => Yii::t('yuncms/balance', 'Refunded'),
            'amount' => Yii::t('yuncms/balance', 'Amount'),
            'user_fee' => Yii::t('yuncms/balance', 'User Fee'),
            'charge_id' => Yii::t('yuncms/balance', 'Charge ID'),
            'balance_bonus_id' => Yii::t('yuncms/balance', 'Balance Bonus ID'),
            'balance_transaction_id' => Yii::t('yuncms/balance', 'Balance Transaction ID'),
            'description' => Yii::t('yuncms/balance', 'Description'),
            'metadata' => Yii::t('yuncms/balance', 'Metadata'),
            'created_at' => Yii::t('yuncms/balance', 'Created At'),
            'succeeded_at' => Yii::t('yuncms/balance', 'Succeeded At'),
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
    public function getRefunds()
    {
        return $this->hasMany(TransactionRefund::class, ['charge_order_no' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBalanceTransaction()
    {
        return $this->hasOne(BalanceTransaction::class, ['id' => 'balance_transaction_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCharge()
    {
        return $this->hasOne(TransactionCharge::class, ['id' => 'charge_id']);
    }

    /**
     * @inheritdoc
     * @return BalanceRechargesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BalanceRechargesQuery(get_called_class());
    }

    /**
     * 是否是作者
     * @return bool
     */
    public function getIsAuthor()
    {
        return $this->user_id == Yii::$app->user->id;
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

    /**
     * 生成一个独一无二的标识
     */
    protected function generateSlug()
    {
        $result = sprintf("%u", crc32($this->id));
        $slug = '';
        while ($result > 0) {
            $s = $result % 62;
            if ($s > 35) {
                $s = chr($s + 61);
            } elseif ($s > 9 && $s <= 35) {
                $s = chr($s + 55);
            }
            $slug .= $s;
            $result = floor($result / 62);
        }
        //return date('YmdHis') . $slug;
        return $slug;
    }

    /**
     * 获取模型总数
     * @param null|int $duration 缓存时间
     * @return int get the model rows
     */
    public static function getTotal($duration = null)
    {
        $total = static::getDb()->cache(function (Connection $db) {
            return static::find()->count();
        }, $duration, new ChainedDependency([
            'dependencies' => new DbDependency(['db' => self::getDb(), 'sql' => 'SELECT MAX(id) FROM ' . self::tableName()])
        ]));
        return $total;
    }

    /**
     * 获取模型今日新增总数
     * @param null|int $duration 缓存时间
     * @return int
     */
    public static function getTodayTotal($duration = null)
    {
        $total = static::getDb()->cache(function (Connection $db) {
            return static::find()->where(['between', 'created_at', DateHelper::todayFirstSecond(), DateHelper::todayLastSecond()])->count();
        }, $duration, new ChainedDependency([
            'dependencies' => new DbDependency(['db' => self::getDb(), 'sql' => 'SELECT MAX(created_at) FROM ' . self::tableName()])
        ]));
        return $total;
    }
}

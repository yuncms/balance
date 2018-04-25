<?php

namespace yuncms\balance\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yuncms\behaviors\JsonBehavior;
use yuncms\transaction\models\TransactionChannel;
use yuncms\user\models\User;
use yuncms\validators\JsonValidator;

/**
 * This is the model class for table "{{%user_settle_account}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $channel
 * @property string $recipient
 *
 * @property User $user
 */
class UserSettleAccount extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_settle_account}}';
    }

    /**
     * 定义行为
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['user'] = [
            'class' => BlameableBehavior::class,
            'attributes' => [
                ActiveRecord::EVENT_BEFORE_INSERT => ['user_id']
            ],
        ];
        $behaviors['recipient'] = [
            'class' => JsonBehavior::class,
            'attributes' => ['recipient'],
        ];
        return $behaviors;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'channel'], 'required'],
            [['user_id'], 'integer'],
            [['channel'], 'string', 'max' => 64],
            [['channel'], 'validateChannel'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['recipient'], JsonValidator::class],
        ];
    }

    public function validateChannel()
    {
        if (static::findOne(['user_id' => $this->user_id, 'channel' => $this->channel]) != null) {
            $this->addError('channel', 'This channel already exists.');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yuncms/balance', 'ID'),
            'user_id' => Yii::t('yuncms/balance', 'User Id'),
            'channel' => Yii::t('yuncms/balance', 'Channel Identity'),
            'recipient' => Yii::t('yuncms/balance', 'Recipient'),
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
     * @return UserSettleAccountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserSettleAccountQuery(get_called_class());
    }
}

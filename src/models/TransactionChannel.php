<?php

namespace yuncms\transaction\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\UnknownClassException;
use yii\behaviors\TimestampBehavior;
use yuncms\base\JsonObject;
use yuncms\behaviors\JsonBehavior;
use yuncms\db\ActiveRecord;
use yuncms\transaction\contracts\ChannelInterface;
use yuncms\validators\JsonValidator;

/**
 * This is the model class for table "{{%transaction_channels}}".
 *
 * @property int $id Id
 * @property string $identity Channel Identity
 * @property string $name Channel Name
 * @property string $title Channel Title
 * @property string $description Channel Description
 * @property string $className Channel className
 * @property JsonObject $configuration Channel configuration
 * @property boolean $status
 * @property int $created_at Created At
 * @property int $updated_at Updated At
 */
class TransactionChannel extends ActiveRecord
{
    const STATUS_ACTIVE = 0b0;
    const STATUS_DISABLED = 0b1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transaction_channels}}';
    }

    /**
     * 定义行为
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => TimestampBehavior::class,
            'configuration' => [
                'class' => JsonBehavior::class,
                'attributes' => ['configuration'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identity', 'name', 'title'], 'required'],
            [['identity', 'name', 'title'], 'string', 'max' => 64],
            [['description'], 'string', 'max' => 255],
            [['className'], 'classExists'],
            [['configuration'], JsonValidator::class],

            ['status', 'default', 'value' => self::STATUS_DISABLED],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DISABLED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yuncms', 'Id'),
            'identity' => Yii::t('yuncms/transaction', 'Channel Identity'),
            'name' => Yii::t('yuncms/transaction', 'Channel Name'),
            'title' => Yii::t('yuncms/transaction', 'Channel Title'),
            'description' => Yii::t('yuncms/transaction', 'Description'),
            'className' => Yii::t('yuncms/transaction', 'Channel ClassName'),
            'configuration' => Yii::t('yuncms/transaction', 'Channel Configuration'),
            'status' => Yii::t('yuncms', 'Status'),
            'created_at' => Yii::t('yuncms/transaction', 'Created At'),
            'updated_at' => Yii::t('yuncms/transaction', 'Updated At'),
        ];
    }

    /**
     * Validate class exists
     */
    public function classExists()
    {
        if (!class_exists($this->className)) {
            $message = Yii::t('yuncms', "Unknown class '{class}'", ['class' => $this->className]);
            $this->addError('className', $message);
            return;
        }
        if (!is_subclass_of($this->className, 'yuncms\transaction\contracts\ChannelInterface')) {
            $message = Yii::t('yuncms/transaction', "'{class}' must extend from 'yuncms\\transaction\\contracts\\ChannelInterface' or its child class", [
                'class' => $this->className]);
            $this->addError('className', $message);
        }
    }

    /**
     * 获取支付网关实例
     * @param string $identity
     * @return object|ChannelInterface
     * @throws InvalidConfigException
     * @throws UnknownClassException
     */
    public static function getChannelByIdentity($identity)
    {
        if (($channel = static::findOne(['identity' => $identity])) != null) {
            if ($channel->status == self::STATUS_ACTIVE) {
                if ($channel->configuration) {
                    return Yii::createObject($channel->configuration->toArray());
                } else {
                    throw new InvalidConfigException('The channel is not enabled yet.');
                }
            } else {
                throw new InvalidConfigException('The channel lacks configuration.');
            }
        } else {
            throw new UnknownClassException (Yii::t('yii', 'The channel does not exist.'));
        }
    }

    /**
     * 获取渠道设置模型
     * @return SettingsModel
     */
    public function getSettingsModel()
    {
        /** @var ChannelInterface $channelClass */
        $channelClass = $this->className;
        /** @var SettingsModel $model */
        $model = $channelClass::getSettingsModel();
        $model->setChannel($this);
        if ($this->configuration) {
            $model->setAttributes($this->configuration->toArray(), false);
        }
        return $model;
    }

    /**
     * 保存前执行
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        return parent::beforeSave($insert);
    }

    /**
     * 保存后执行
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     * @return TransactionChannelQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionChannelQuery(get_called_class());
    }
}

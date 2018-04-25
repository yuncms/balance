<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\balance\rest\models;

use yii\base\Model;
use yii\behaviors\BlameableBehavior;
use yuncms\rest\models\User;

/**
 * 余额提现接口
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class BalanceWithdrawal extends \yuncms\balance\models\BalanceWithdrawal
{
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
                Model::EVENT_BEFORE_VALIDATE => ['user_id']
            ],
        ];
        return $behaviors;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
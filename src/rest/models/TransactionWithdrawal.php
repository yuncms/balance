<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\transaction\rest\models;

use yii\base\Model;
use yii\behaviors\BlameableBehavior;
use yuncms\db\ActiveRecord;

/**
 * 余额提现接口
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class TransactionWithdrawal extends \yuncms\transaction\models\TransactionWithdrawal
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
}
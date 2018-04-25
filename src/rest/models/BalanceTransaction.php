<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\balance\rest\models;

use yuncms\rest\models\User;

/**
 * 余额明细
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class BalanceTransaction extends \yuncms\balance\models\BalanceTransaction
{

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * 扩展字段定义
     * @return array
     */
    public function extraFields()
    {
        return ['user'];
    }
}
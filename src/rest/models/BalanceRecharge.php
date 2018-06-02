<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\balance\rest\models;

use yuncms\rest\models\User;

/**
 * Class BalanceRecharge
 * @package yuncms\balance\rest\models
 */
class BalanceRecharge extends \yuncms\balance\models\BalanceRecharge
{

    public function fields()
    {
        return [
            'id',
            'succeeded',
            'amount',
            'description',
            'channel',
            'metadata',
            'created_at',
            'balance_transaction_id',
            'user',
            'charge'
        ];
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

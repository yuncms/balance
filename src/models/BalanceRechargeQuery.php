<?php

namespace yuncms\balance\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[BalanceRecharge]].
 *
 * @see BalanceRecharge
 */
class BalanceRechargeQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /*public function active()
    {
        return $this->andWhere(['status' => TransactionRecharge::STATUS_PUBLISHED]);
    }*/

    /**
     * @inheritdoc
     * @return BalanceRecharge[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BalanceRecharge|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

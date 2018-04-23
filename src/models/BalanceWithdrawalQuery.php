<?php

namespace yuncms\balance\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[BalanceWithdrawal]].
 *
 * @see BalanceWithdrawal
 */
class BalanceWithdrawalQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /*public function active()
    {
        return $this->andWhere(['status' => TransactionWithdrawal::STATUS_PUBLISHED]);
    }*/

    /**
     * @inheritdoc
     * @return BalanceWithdrawal[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BalanceWithdrawal|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

<?php

namespace yuncms\balance\models;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[TransactionSettleAccount]].
 *
 * @see TransactionSettleAccount
 */
class UserSettleAccountQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /*public function active()
    {
        return $this->andWhere(['status' => TransactionSettleAccount::STATUS_PUBLISHED]);
    }*/

    /**
     * @inheritdoc
     * @return BalanceSettleAccount[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BalanceSettleAccount|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

<?php

namespace yuncms\transaction\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[TradeCharges]].
 *
 * @see TransactionCharge
 */
class TransactionChargeQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TransactionCharge[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TransactionCharge|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

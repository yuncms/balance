<?php

namespace yuncms\transaction\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[TradeRefunds]].
 *
 * @see TransactionRefund
 */
class TransactionRefundQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TransactionRefund[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TransactionRefund|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

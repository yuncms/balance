<?php

namespace yuncms\transaction\models;

/**
 * This is the ActiveQuery class for [[TransactionBalanceTransaction]].
 *
 * @see TransactionBalanceTransaction
 */
class TransactionBalanceTransactionQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return TransactionBalanceTransaction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TransactionBalanceTransaction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

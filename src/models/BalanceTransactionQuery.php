<?php

namespace yuncms\balance\models;

/**
 * This is the ActiveQuery class for [[TransactionBalanceTransaction]].
 *
 * @see BalanceTransaction
 */
class BalanceTransactionQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return BalanceTransaction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BalanceTransaction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

<?php

namespace yuncms\transaction\models;

/**
 * This is the ActiveQuery class for [[TransactionBalanceTransfer]].
 *
 * @see TransactionBalanceTransfer
 */
class TransactionBalanceTransferQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /*public function active()
    {
        return $this->andWhere(['status' => TransactionBalanceTransfer::STATUS_PUBLISHED]);
    }*/

    /**
     * @inheritdoc
     * @return TransactionBalanceTransfer[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TransactionBalanceTransfer|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

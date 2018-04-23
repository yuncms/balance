<?php

namespace yuncms\balance\models;

/**
 * This is the ActiveQuery class for [[TransactionBalanceTransfer]].
 *
 * @see BalanceTransfer
 */
class BalanceTransferQuery extends \yii\db\ActiveQuery
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
     * @return BalanceTransfer[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BalanceTransfer|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

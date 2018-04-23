<?php

namespace yuncms\transaction\models;

/**
 * This is the ActiveQuery class for [[TransactionSettleAccount]].
 *
 * @see TransactionSettleAccount
 */
class TransactionSettleAccountQuery extends \yii\db\ActiveQuery
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
     * @return TransactionSettleAccount[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TransactionSettleAccount|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

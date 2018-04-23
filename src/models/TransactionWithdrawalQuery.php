<?php

namespace yuncms\transaction\models;

/**
 * This is the ActiveQuery class for [[TransactionWithdrawal]].
 *
 * @see TransactionWithdrawal
 */
class TransactionWithdrawalQuery extends \yii\db\ActiveQuery
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
     * @return TransactionWithdrawal[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TransactionWithdrawal|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

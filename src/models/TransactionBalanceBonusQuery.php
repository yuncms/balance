<?php

namespace yuncms\transaction\models;

/**
 * This is the ActiveQuery class for [[TransactionBalanceBonus]].
 *
 * @see TransactionBalanceBonus
 */
class TransactionBalanceBonusQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /*public function active()
    {
        return $this->andWhere(['status' => TransactionBalanceBonus::STATUS_PUBLISHED]);
    }*/

    /**
     * @inheritdoc
     * @return TransactionBalanceBonus[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TransactionBalanceBonus|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

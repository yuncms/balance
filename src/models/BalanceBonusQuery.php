<?php

namespace yuncms\balance\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[BalanceBonus]].
 *
 * @see BalanceBonus
 */
class BalanceBonusQuery extends ActiveQuery
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
     * @return BalanceBonus[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BalanceBonus|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

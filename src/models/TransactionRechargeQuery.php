<?php

namespace yuncms\transaction\models;

/**
 * This is the ActiveQuery class for [[TransactionRecharge]].
 *
 * @see TransactionRecharge
 */
class TransactionRechargeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /*public function active()
    {
        return $this->andWhere(['status' => TransactionRecharge::STATUS_PUBLISHED]);
    }*/

    /**
     * @inheritdoc
     * @return TransactionRecharge[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TransactionRecharge|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

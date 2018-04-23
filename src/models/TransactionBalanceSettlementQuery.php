<?php

namespace yuncms\transaction\models;

/**
 * This is the ActiveQuery class for [[TransactionBalanceSettlement]].
 *
 * @see TransactionBalanceSettlement
 */
class TransactionBalanceSettlementQuery extends \yii\db\ActiveQuery
{
    /**
     * 获取已经结算
     * @return $this
     */
    public function succeeded()
    {
        return $this->andWhere(['status' => TransactionBalanceSettlement::STATUS_SUCCEEDED]);
    }

    /**
     * 获取已入账
     * @return $this
     */
    public function credited()
    {
        return $this->andWhere(['status' => TransactionBalanceSettlement::STATUS_CREDITED]);
    }

    /**
     * 获取未结算
     * @return $this
     */
    public function created()
    {
        return $this->andWhere(['status' => TransactionBalanceSettlement::STATUS_CREATED]);
    }

    /**
     * @inheritdoc
     * @return TransactionBalanceSettlement[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TransactionBalanceSettlement|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

<?php

namespace yuncms\balance\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[BalanceSettlement]].
 *
 * @see BalanceSettlement
 */
class BalanceSettlementQuery extends ActiveQuery
{
    /**
     * 获取已经结算
     * @return $this
     */
    public function succeeded()
    {
        return $this->andWhere(['status' => BalanceSettlement::STATUS_SUCCEEDED]);
    }

    /**
     * 获取已入账
     * @return $this
     */
    public function credited()
    {
        return $this->andWhere(['status' => BalanceSettlement::STATUS_CREDITED]);
    }

    /**
     * 获取未结算
     * @return $this
     */
    public function created()
    {
        return $this->andWhere(['status' => BalanceSettlement::STATUS_CREATED]);
    }

    /**
     * @inheritdoc
     * @return BalanceSettlement[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BalanceSettlement|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

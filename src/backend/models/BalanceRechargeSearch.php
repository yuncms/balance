<?php

namespace yuncms\balance\backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use yuncms\balance\models\BalanceRecharge;
use yii\base\Model;

/**
 * BalanceRechargeSearch represents the model behind the search form about `yuncms\balance\models\BalanceRecharge`.
 */
class BalanceRechargeSearch extends BalanceRecharge
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'succeeded', 'refunded', 'balance_transaction_id', 'created_at', 'succeeded_at'], 'integer'],
            [['amount', 'user_fee', 'balance_bonus_id'], 'number'],
            [['channel', 'charge_id', 'description', 'metadata'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = BalanceRecharge::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'id' => SORT_ASC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'succeeded' => $this->succeeded,
            'refunded' => $this->refunded,
            'amount' => $this->amount,
            'user_fee' => $this->user_fee,
            'balance_bonus_id' => $this->balance_bonus_id,
            'balance_transaction_id' => $this->balance_transaction_id,
            'created_at' => $this->created_at,
            'succeeded_at' => $this->succeeded_at,
        ]);

        $query->andFilterWhere(['like', 'channel', $this->channel])
            ->andFilterWhere(['like', 'charge_id', $this->charge_id])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'metadata', $this->metadata]);

        return $dataProvider;
    }
}

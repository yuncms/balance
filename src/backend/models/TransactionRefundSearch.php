<?php

namespace yuncms\transaction\backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use yuncms\transaction\models\TransactionRefund;
use yii\base\Model;

/**
 * TransactionRefundSearch represents the model behind the search form about `yuncms\transaction\models\TransactionRefund`.
 */
class TransactionRefundSearch extends TransactionRefund
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'amount', 'time_succeed'], 'integer'],
            [['succeed', 'status', 'description', 'failure_code', 'failure_msg', 'charge_id', 'charge_order_no', 'transaction_no', 'funding_source', 'created_at'], 'safe'],
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
        $query = TransactionRefund::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'amount' => $this->amount,
            'time_succeed' => $this->time_succeed,
        ]);

        if ($this->created_at !== null) {
            $date = strtotime($this->created_at);
            $query->andWhere(['between', 'created_at', $date, $date + 3600 * 24]);
        }

        $query->andFilterWhere(['like', 'succeed', $this->succeed])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'failure_code', $this->failure_code])
            ->andFilterWhere(['like', 'failure_msg', $this->failure_msg])
            ->andFilterWhere(['like', 'charge_id', $this->charge_id])
            ->andFilterWhere(['like', 'charge_order_no', $this->charge_order_no])
            ->andFilterWhere(['like', 'transaction_no', $this->transaction_no])
            ->andFilterWhere(['like', 'funding_source', $this->funding_source]);

        return $dataProvider;
    }
}

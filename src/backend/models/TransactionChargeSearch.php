<?php

namespace yuncms\transaction\backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use yuncms\transaction\models\TransactionCharge;
use yii\base\Model;

/**
 * TransactionChargeSearch represents the model behind the search form about `yuncms\transaction\models\TransactionCharge`.
 */
class TransactionChargeSearch extends TransactionCharge
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'paid', 'refunded', 'reversed', 'channel', 'order_no', 'currency', 'subject', 'body', 'client_ip', 'transaction_no', 'failure_code', 'failure_msg', 'metadata', 'description', 'created_at'], 'safe'],
            [['amount', 'time_paid', 'time_expire', 'amount_refunded'], 'integer'],
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
        $query = TransactionCharge::find();

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
            'amount' => $this->amount,
            'time_paid' => $this->time_paid,
            'time_expire' => $this->time_expire,
            'amount_refunded' => $this->amount_refunded,
        ]);

        if ($this->created_at !== null) {
            $date = strtotime($this->created_at);
            $query->andWhere(['between', 'created_at', $date, $date + 3600 * 24]);
        }

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'paid', $this->paid])
            ->andFilterWhere(['like', 'refunded', $this->refunded])
            ->andFilterWhere(['like', 'reversed', $this->reversed])
            ->andFilterWhere(['like', 'channel', $this->channel])
            ->andFilterWhere(['like', 'order_no', $this->order_no])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['like', 'client_ip', $this->client_ip])
            ->andFilterWhere(['like', 'transaction_no', $this->transaction_no])
            ->andFilterWhere(['like', 'failure_code', $this->failure_code])
            ->andFilterWhere(['like', 'failure_msg', $this->failure_msg])
            ->andFilterWhere(['like', 'metadata', $this->metadata])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}

<?php

namespace yuncms\transaction\backend\models;

use yii\data\ActiveDataProvider;
use yuncms\transaction\models\TransactionWithdrawal;
use yii\base\Model;

/**
 * TransactionWithdrawalSearch represents the model behind the search form about `yuncms\transaction\models\TransactionWithdrawal`.
 */
class TransactionWithdrawalSearch extends TransactionWithdrawal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status'], 'integer'],
            [['amount'], 'number'],
            [['channel', 'created_at', 'canceled_at', 'succeeded_at'], 'safe'],
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
        $query = TransactionWithdrawal::find();

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
            'status' => $this->status,
            'amount' => $this->amount,
        ]);

        if ($this->created_at !== null) {
            $date = strtotime($this->created_at);
            $query->andWhere(['between', 'created_at', $date, $date + 3600 * 24]);
        }

        if ($this->canceled_at !== null) {
            $date = strtotime($this->canceled_at);
            $query->andWhere(['between', 'canceled_at', $date, $date + 3600 * 24]);
        }

        if ($this->succeeded_at !== null) {
            $date = strtotime($this->succeeded_at);
            $query->andWhere(['between', 'succeeded_at', $date, $date + 3600 * 24]);
        }

        $query->andFilterWhere(['like', 'channel', $this->channel]);

        return $dataProvider;
    }
}

<?php

namespace frontend\Models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\Models\stadium;

/**
 * SearchStadium represents the model behind the search form of `frontend\Models\stadium`.
 */
class SearchStadium extends stadium
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address', 'iphone', 'creat_time', 'stadium_name'], 'safe'],
            [['id'], 'integer'],
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
        $query = stadium::find();

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
            'creat_time' => $this->creat_time,
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'iphone', $this->iphone])
            ->andFilterWhere(['like', 'stadium_name', $this->stadium_name]);

        return $dataProvider;
    }
}

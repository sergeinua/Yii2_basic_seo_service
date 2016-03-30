<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ApiCity;

/**
 * ApiCitySearch represents the model behind the search form about `app\models\ApiCity`.
 */
class ApiCitySearch extends ApiCity
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id', 'visits', 'created_at'], 'integer'],
            [['country_iso'], 'string'],
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
        $query = ApiCity::find();

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
            'city_id' => $this->city_id,
            'visits' => $this->visits,
            'created_at' => $this->created_at,
            'country_iso' => $this->country_iso,
        ]);

        return $dataProvider;
    }
}

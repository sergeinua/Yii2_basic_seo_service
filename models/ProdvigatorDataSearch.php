<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProdvigatorData;

/**
 * ProdvigatorDataSearch represents the model behind the search form about `app\models\ProdvigatorData`.
 */
class ProdvigatorDataSearch extends ProdvigatorData
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'domain'], 'safe'],
            [['keywords', 'traff', 'new_keywords', 'out_keywords', 'rised_keywords', 'down_keywords', 'ad_keywords', 'ads', 'date'], 'integer'],
            [['visible', 'cost_min', 'cost_max'], 'number'],
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
        $query = ProdvigatorData::find();

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
            'keywords' => $this->keywords,
            'traff' => $this->traff,
            'new_keywords' => $this->new_keywords,
            'out_keywords' => $this->out_keywords,
            'rised_keywords' => $this->rised_keywords,
            'down_keywords' => $this->down_keywords,
            'visible' => $this->visible,
            'cost_min' => $this->cost_min,
            'cost_max' => $this->cost_max,
            'ad_keywords' => $this->ad_keywords,
            'ads' => $this->ads,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'domain', $this->domain]);

        return $dataProvider;
    }
}

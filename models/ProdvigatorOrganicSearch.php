<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProdvigatorOrganic;

/**
 * ProdvigatorOrganicSearch represents the model behind the search form about `app\models\ProdvigatorOrganic`.
 */
class ProdvigatorOrganicSearch extends ProdvigatorOrganic
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'region_queries_count', 'dynamic', 'found_results', 'url_crc', 'concurrency', 'position', 'date', 'keyword_id', 'region_queries_count_wide', 'geo_names'], 'integer'],
            [['domain', 'keyword', 'url', 'right_spell', 'subdomain', 'types'], 'safe'],
            [['cost'], 'number'],
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
        $query = ProdvigatorOrganic::find();

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
            'region_queries_count' => $this->region_queries_count,
            'dynamic' => $this->dynamic,
            'found_results' => $this->found_results,
            'url_crc' => $this->url_crc,
            'cost' => $this->cost,
            'concurrency' => $this->concurrency,
            'position' => $this->position,
            'date' => $this->date,
            'keyword_id' => $this->keyword_id,
            'region_queries_count_wide' => $this->region_queries_count_wide,
            'geo_names' => $this->geo_names,
        ]);

        $query->andFilterWhere(['like', 'domain', $this->domain])
            ->andFilterWhere(['like', 'keyword', $this->keyword])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'right_spell', $this->right_spell])
            ->andFilterWhere(['like', 'subdomain', $this->subdomain])
            ->andFilterWhere(['like', 'types', $this->types]);

        return $dataProvider;
    }
}

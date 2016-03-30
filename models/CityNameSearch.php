<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CityName;

/**
 * CityNameSearch represents the model behind the search form about `app\models\CityName`.
 */
class CityNameSearch extends CityName
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['criteriaId', 'parentId', 'countryCode'], 'integer'],
            [['name', 'canonicalName', 'targetType', 'status'], 'safe'],
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
        $query = CityName::find();

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
            'criteriaId' => $this->criteriaId,
            'parentId' => $this->parentId,
            'countryCode' => $this->countryCode,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'canonicalName', $this->canonicalName])
            ->andFilterWhere(['like', 'targetType', $this->targetType])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}

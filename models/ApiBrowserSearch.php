<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ApiBrowser;

/**
 * ApiBrowserSearch represents the model behind the search form about `app\models\ApiBrowser`.
 */
class ApiBrowserSearch extends ApiBrowser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pageviews', 'visits', 'browserVersion', 'date'], 'integer'],
            [['browser'], 'safe'],
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
        $query = ApiBrowser::find();

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
            'pageviews' => $this->pageviews,
            'visits' => $this->visits,
            'browserVersion' => $this->browserVersion,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'browser', $this->browser]);

        return $dataProvider;
    }
}

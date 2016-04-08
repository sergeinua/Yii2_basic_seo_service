<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ApiSessions;

/**
 * ApiSessionsSearch represents the model behind the search form about `app\models\ApiSessions`.
 */
class ApiSessionsSearch extends ApiSessions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pageviews', 'bounces', 'session_duration_bucket', 'project_id', 'date'], 'integer'],
            [['session_duration'], 'safe'],
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
        $query = ApiSessions::find();

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
            'bounces' => $this->bounces,
            'session_duration_bucket' => $this->session_duration_bucket,
            'project_id' => $this->project_id,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'session_duration', $this->session_duration]);

        return $dataProvider;
    }
}

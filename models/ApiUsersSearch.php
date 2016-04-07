<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ApiUsers;

/**
 * ApiUsersSearch represents the model behind the search form about `app\models\ApiUsers`.
 */
class ApiUsersSearch extends ApiUsers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'users', 'new_users', 'session_count', 'project_id', 'date'], 'integer'],
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
        $query = ApiUsers::find();

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
            'users' => $this->users,
            'new_users' => $this->new_users,
            'session_count' => $this->session_count,
            'project_id' => $this->project_id,
            'date' => $this->date,
        ]);

        return $dataProvider;
    }
}

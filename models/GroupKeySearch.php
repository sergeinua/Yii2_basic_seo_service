<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\GroupKey;

/**
 * GroupKeySearch represents the model behind the search form about `app\models\GroupKey`.
 */
class GroupKeySearch extends GroupKey
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'key_id'], 'integer'],
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
        $query = GroupKey::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'group_id' => $this->group_id,
            'key_id' => $this->key_id,
        ]);

        return $dataProvider;
    }
}

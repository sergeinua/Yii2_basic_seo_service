<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\KeyPosition;

/**
 * KeyPositionSearch represents the model behind the search form about `app\models\KeyPosition`.
 */
class KeyPositionSearch extends KeyPosition
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'key_id', 'position'], 'integer'],
            [['date'], 'safe'],
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
        $query = KeyPosition::find();

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
            'id' => $this->id,
            'key_id' => $this->key_id,
            'date' => $this->date,
            'position' => $this->position,
        ]);

        return $dataProvider;
    }
}

<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ApiDevice;

/**
 * ApiDeviceSearch represents the model behind the search form about `app\models\ApiDevice`.
 */
class ApiDeviceSearch extends ApiDevice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'visits', 'project_id', 'date'], 'integer'],
            [['brand'], 'safe'],
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
        $query = ApiDevice::find();

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
            'visits' => $this->visits,
            'project_id' => $this->project_id,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'brand', $this->brand]);

        return $dataProvider;
    }
}

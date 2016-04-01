<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Projects;

/**
 * ProjectsSearch represents the model behind the search form about `app\models\Projects`.
 */
class ProjectsSearch extends Projects
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['title', 'description'], 'safe'],
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
        $query = Projects::find();

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
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        /**
         * all projects're visible for admin only
         * the rest can access only defined (via ProjectUser model) projects
        */
        if(Yii::$app->user->identity->role == 'admin') {
            $user_id = Yii::$app->user->identity->id;
            $project_list = ProjectUser::find()->where(['user_id' => $user_id])->all();
            $projects = [];
            foreach($project_list as $key => $value) :
                $projects[$key] = $value->project_id;
            endforeach;

            $query->andFilterWhere(['id' => $projects]);
        }

        return $dataProvider;
    }
}

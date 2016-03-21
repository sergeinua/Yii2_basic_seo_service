<?php

namespace app\controllers;

use app\models\GroupKey;
use app\models\Keys;
use app\models\ProjectGroup;
use Yii;
use app\models\ProjectVisibility;
use app\models\ProjectVisibilitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProjectVisibilityController implements the CRUD actions for ProjectVisibility model.
 */
class ProjectVisibilityController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all ProjectVisibility models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectVisibilitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProjectVisibility model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ProjectVisibility model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProjectVisibility();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ProjectVisibility model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ProjectVisibility model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProjectVisibility model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProjectVisibility the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProjectVisibility::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * Updates the percentage of the key position items visibility
     *
     */
    public function actionUpdatePosition()
    {
        echo '<pre>';
        $request = Yii::$app->request->get();
        $project_id = $request['project_id'];
        $groups = ProjectGroup::find()->where(['project_id' => $project_id])->all();
        foreach($groups as $group){
            $group_keys = GroupKey::find()->where(['group_id' => $group->group_id])->all();
        }
        $top_ten=0;
        foreach($group_keys as $group_key){
            $keys = Keys::find()->where(['id' => $group_key->key_id])->one();
            if($keys->position->position <= 10)
                $top_ten++;
        }
        $top_ten = $top_ten / count($group_keys) * 100;

        $date = date('dmY');
        $id = md5($project_id . $date);
        $exists = ProjectVisibility::find()->where(['id' => $id])->exists();

        if($exists) {
            $model = $this->findModel($id);
            $model->visibility = $top_ten;
            $model->update($model->id);
        } else {
            $model = new ProjectVisibility();
            $model->project_id = $project_id;
            $model->date = $date;
            $model->id = $id;
            $model->visibility = $top_ten;
            $model->save();
        }
        return $this->redirect(Yii::$app->request->referrer);
    }
}

<?php

namespace app\controllers;

use app\models\GroupKey;
use app\models\KeyPosition;
use app\models\Keys;
use Yii;
use app\models\GroupVisibility;
use app\models\GroupVisibilitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GroupVisibilityController implements the CRUD actions for GroupVisibility model.
 */
class GroupVisibilityController extends Controller
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
     * Lists all GroupVisibility models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GroupVisibilitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GroupVisibility model.
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
     * Creates a new GroupVisibility model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GroupVisibility();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GroupVisibility model.
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
     * Deletes an existing GroupVisibility model.
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
     * Finds the GroupVisibility model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GroupVisibility the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GroupVisibility::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpdate_position()
    {
        $request = Yii::$app->request->get();
        global $group_id;
        $group_id = $request['group_id'];
        $group_key = GroupKey::find()->where(['group_id' => $group_id])->all();
        // getting all the needed keys of the group
        $i = 0;
        for($i=0; $i < count($group_key); $i++){
            $key[$i] = KeyPosition::find()->where(['key_id' => $group_key[$i]['key_id']])->orderBy('date desc')->one();
        }
        // counting elements for top
        $i = 0;
        $included = 0;
        for($i=0; $i < count($key); $i++){
            if($key[$i]['position'] <= 10)
                $included++;
        }
        // percentage counted
        $top = $included / count($key) * 100;

        $date = date('dmY');
        $id = md5($group_id . $date);
        // TODO: check if exists current date
        $exists = GroupVisibility::find()->where(['id' => $id])->exists();

        if($exists) {
            $model = $this->findModel($id);
            $model->visibility = $top;
            $model->update($model->id);
        } else {
            $model = new GroupVisibility();
            $model->group_id = $group_id;
            $model->date = $date;
            $model->id = $id;
            $model->visibility = $top;
            $model->save();
        }


        return $this->redirect(Yii::$app->request->referrer);

    }
}

<?php

namespace app\controllers;

use app\models\GroupVisibility;
use Yii;
use app\models\Groups;
use app\models\GroupsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\GroupsForm;
use app\components\Google\Api\CustomSearch;
use yii\helpers\Json;
use yii\helpers\Html;
use DateTime;
use yii\filters\AccessControl;

/**
 * GroupsController implements the CRUD actions for Groups model.
 */
class GroupsController extends Controller
{
    public $layout = '@app/views/layouts/main-admin.php';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['seo'],
                    ],
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Groups models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GroupsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Groups model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        $gr_vis_model = GroupVisibility::find()->where(['group_id' => $id])->orderBy('date desc')->all();

        if($periodForKeysFrom = Yii::$app->getRequest()->post('periodForKeysFrom')) {
            $periodForKeysFrom = DateTime::createFromFormat('Y-m-d', $periodForKeysFrom)->format('dmY');
        }
        if($periodForKeysTill = Yii::$app->getRequest()->post('periodForKeysTill')) {
            $periodForKeysTill = DateTime::createFromFormat('Y-m-d', $periodForKeysTill)->format('dmY');
        }

        if($periodForKeysFrom){
            $gr_vis_model = GroupVisibility::find()->where(['group_id' => $id])->orderBy('date desc')
                ->andFilterWhere(['>=', 'date', $periodForKeysFrom])->all();
        }
        if($periodForKeysTill){
            $gr_vis_model = GroupVisibility::find()->where(['group_id' => $id])->orderBy('date desc')
                ->andFilterWhere(['<=', 'date', $periodForKeysTill])->all();
        }
        if($periodForKeysFrom and $periodForKeysTill){
            $gr_vis_model = GroupVisibility::find()->where(['group_id' => $id])->orderBy('date desc')
                ->andFilterWhere(['between', 'date', $periodForKeysFrom, $periodForKeysTill])->all();
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'gr_vis_model' => $gr_vis_model,
            'periodForKeysFrom' => $periodForKeysFrom,
            'periodForKeysTill' => $periodForKeysTill,
        ]);
    }

    /**
     * Creates a new Groups model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GroupsForm();
        $isNewRecord = true;
        $project_id = Yii::$app->request->get('project_id');
        $model->project_id = isset($project_id) ? $project_id : null;

        if ($model->load(Yii::$app->request->post())) {
            $groupModel = $model->save();
            return $this->redirect(['view', 'id' => $groupModel->id]);
        } else {
            return $this->render('groups', [
                'model' => $model,
                'isNewRecord' => $isNewRecord,
            ]);
        }
    }

    /**
     * Updates an existing Groups model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $modelGroup = $this->findModel($id);
        $model = new GroupsForm();

        if ($model->load(Yii::$app->getRequest()->post())) {
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $isNewRecord = true;
            if($modelGroup) {
                $isNewRecord = false;
                $model->load($modelGroup->toArray(), '');
            }
            $model->project_id = $modelGroup->project->id;
            return $this->render('groups', [
                'model' => $model,
                'isNewRecord' => $isNewRecord,
            ]);
        }
    }

    /**
     * Deletes an existing Groups model.
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
     * Finds the Groups model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Groups the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Groups::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

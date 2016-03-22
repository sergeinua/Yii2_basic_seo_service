<?php

namespace app\controllers;

use app\models\ProjectVisibility;
use Yii;
use app\models\Projects;
use app\models\ProjectsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use DateTime;
use yii\filters\AccessControl;

/**
 * ProjectsController implements the CRUD actions for Projects model.
 */
class ProjectsController extends Controller
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
     * Lists all Projects models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Projects model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        // none of the periods are defined
        $project_vis_model = ProjectVisibility::find()->where(['project_id' => $id])->orderBy('date desc')->all();

        if($periodFrom = Yii::$app->getRequest()->post('periodForProjectFrom'))
            $periodFrom = DateTime::createFromFormat('Y-m-d', $periodFrom)->format('dmY');
        if($periodTill = Yii::$app->getRequest()->post('periodForProjectTill'))
            $periodTill = DateTime::createFromFormat('Y-m-d', $periodTill)->format('dmY');

        //period from is defined
        if($periodFrom){
            $project_vis_model = ProjectVisibility::find()->where(['project_id' => $id])->orderBy('date desc')
                ->andFilterWhere(['>=', 'date', $periodFrom])->all();
        }
        //period till is defined
        if($periodTill){
            $project_vis_model = ProjectVisibility::find()->where(['project_id' => $id])->orderBy('date desc')
                ->andFilterWhere(['<=', 'date', $periodTill])->all();
        }
        //periods from & till are defined
        if($periodFrom and $periodTill){
            $project_vis_model = ProjectVisibility::find()->where(['project_id' => $id])->orderBy('date desc')
                ->andFilterWhere(['between', 'date', $periodFrom, $periodTill])->all();
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'project_vis_model' => $project_vis_model,
            'periodFrom' => $periodFrom,
            'periodTill' => $periodTill,
        ]);
    }

    /**
     * Creates a new Projects model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Projects();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Projects model.
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
     * Deletes an existing Projects model.
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
     * Finds the Projects model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Projects the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Projects::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

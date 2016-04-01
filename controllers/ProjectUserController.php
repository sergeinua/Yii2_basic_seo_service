<?php

namespace app\controllers;

use app\models\Projects;
use app\models\User;
use Yii;
use app\models\ProjectUser;
use app\models\ProjectUserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProjectUserController implements the CRUD actions for ProjectUser model.
 */
class ProjectUserController extends Controller
{
    public $layout = '@app/views/layouts/main-admin.php';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ProjectUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProjectUser model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ProjectUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProjectUser();
        $project_model = Projects::find()->all();
        $project_list = [];
        foreach($project_model as $item) :
            $project_list[$item->id] = $item->title;
        endforeach;
        $user_model = User::find()->all();
        $user_list = [];
        foreach($user_model as $item) :
            $user_list[$item->id] = $item->username;
        endforeach;

        if ($model->load(Yii::$app->request->post())) {
            foreach($user_list as $key => $value) :
                $model->id = md5($key . $model->project_id);
                if(!ProjectUser::find()->where(['id' => $model->id])->exists()){
                    $new_model = new ProjectUser();
                    $new_model->id = md5($key . $model->project_id);
                    $new_model->user_id = $key;
                    $new_model->project_id = $model->project_id;
                    $new_model->save();
                }
            endforeach;
            return $this->redirect(['/project-user/index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'project_list' => $project_list,
                'user_list' => $user_list,
            ]);
        }
    }

    /**
     * Updates an existing ProjectUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
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
     * Deletes an existing ProjectUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProjectUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ProjectUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProjectUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace app\controllers;

use Yii;
use app\models\Groups;
use app\models\GroupsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\GroupsForm;
use app\components\Google\Api\CustomSearch;
use yii\helpers\Json;

/**
 * GroupsController implements the CRUD actions for Groups model.
 */
class GroupsController extends Controller
{
    public $layout = '@app/views/layouts/main-admin.php';

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
        global $project_position;
        $project_link = $this->findModel($id)->project->title;
//        echo '<meta charset = utf8>';
//echo '<pre>';
//
//
//        var_dump($this->findModel($id)->getKeys());
//
//
//
//        die();
//        $apiClient = new CustomSearch();
//        $apiClient->setApiKey('AIzaSyBfA8r3D1hy11k7bdGQrXrMiptZ5MaMnSE');
//        $apiClient->setCustomSearchEngineId('006254468391416147805:-jyqgokuwi8');
//        $apiClient->setQuery('разработка интернет магазина автозапчастей');
//
//
//        $response = $apiClient->executeRequest();
//
//        var_dump($project_link);
//
//        $response = Json::decode($response);
//
//        for ($i=0;$i<10;$i++) {
//
//            if (substr($response['items'][$i]['link'], 0, strlen($project_link)) == $project_link){
//                $project_position = $i +1;
//                break;
//            }
//
//
//
//        }
//        echo $project_position;












        return $this->render('view', [
            'model' => $this->findModel($id),
            'project_position' => $project_position,
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

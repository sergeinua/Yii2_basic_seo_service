<?php

namespace app\controllers;

use app\models\Groups;
use app\models\ProjectGroup;
use app\models\Projects;
use Faker\Provider\DateTime;
use Yii;
use app\models\Keys;
use app\models\KeysSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\KeysForm;
use app\components\Google\Api\CustomSearch;
use yii\helpers\Json;
use app\models\KeyPosition;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;


/**
 * KeysController implements the CRUD actions for Keys model.
 */
class KeysController extends Controller
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
     * Lists all Keys models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KeysSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Keys model.
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
     * Creates a new Keys model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KeysForm();
        $group_id = Yii::$app->request->get('group_id');
        $model->group_id = isset($group_id) ? $group_id : null;
        $isNewRecord = true;

        if ($model->load(Yii::$app->request->post())) {

            $items = trim($model->title);
            $items = explode("\n", $items);
            $items = array_filter($items, 'trim');

            foreach($items as $item){
                $model->title = $item;
                $keyModel = $model->save();

            }

            return $this->redirect(['view', 'id' => $keyModel->id]);
        } else {
            return $this->render('keys', [
                'model' => $model,
                'isNewRecord' => $isNewRecord,
            ]);
        }
    }

    /**
     * Updates an existing Keys model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $modelKey = $this->findModel($id);
        $model = new KeysForm();

        if ($model->load(Yii::$app->getRequest()->post())) {
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $isNewRecord = true;
            if($modelKey) {
                $isNewRecord = false;
                $model->load($modelKey->toArray(), '');
            }
            $model->group_id = $modelKey->group->id;
            return $this->render('keys', [
                'model' => $model,
                'isNewRecord' => $isNewRecord,
            ]);
        }
    }

    /**
     * Deletes an existing Keys model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Keys model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Keys the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Keys::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPlace(){

        global $project_position;
        $request = Yii::$app->request->get();
        $project_link = $request['project_link'];
        $key_id = $request['key_id'];
        $key_title = Keys::find()->where(['id' => $key_id])->one()->title;
        $group_id = $request['group_id'];
        $project_id = ProjectGroup::find()->where(['group_id' => $group_id])->one()->project_id;

        $googlehost = Projects::find()->where(['id' => $project_id])->one()->googlehost;
        $language = Projects::find()->where(['id' => $project_id])->one()->language;

        if(!$googlehost)
            $googlehost = Groups::find()->where(['id' => $group_id])->one()->googlehost;
        if(!$language)
            $language = Groups::find()->where(['id' => $group_id])->one()->language;

        // $start_pos - defining the start position for the google api search
        if($p = KeyPosition::find()->where(['key_id' => $key_id])->orderBy('date DESC')->one()){
            /** @var KeyPosition $p */
            $start = $p->position;
        } else {
            $start = null;
        }
        // the very first time of the position search - empty value
        if ($start == null) {
            for ($i=0; $i<10; $i++){
                $start_pos = $i * 10;

                $result = $this->getDistinctPosition($key_title, $project_link, $start_pos, $googlehost, $language);

                if ($result > 0){
                    $project_position = $result;
                    break;
                }
            }
        } else {
            //the case when position was discovered earlier
            if($start % 10 == 0){
                $start_pos = floor($start / 10 - 1) * 10;
            } else {
                $start_pos = floor($start / 10) * 10;
            }

            $result = $this->getDistinctPosition($key_title, $project_link, $start_pos, $googlehost, $language);

            if (!isset($result)) {
                $result = $this->getDistinctPosition($key_title, $project_link, $start_pos - 10, $googlehost, $language);
            }

            if (!isset($result)) {
                $result = $this->getDistinctPosition($key_title, $project_link, $start_pos + 10, $googlehost, $language);
            }

            if ($result > 0)
                $project_position = $result;
        }

        if ($project_position > 0){
            $model = new KeyPosition();
            $model->key_id = $key_id;
            $model->position = $project_position;
            $model->date = date('U');
            $model->save();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function getDistinctPosition($key_title, $project_link, $start_pos, $googlehost, $language)
    {
        global $project_pos;
        $apiClient = new CustomSearch();
        $apiClient->setApiKey('AIzaSyBfA8r3D1hy11k7bdGQrXrMiptZ5MaMnSE');
        $apiClient->setCustomSearchEngineId('006254468391416147805:-jyqgokuwi8');
        $apiClient->setQuery($key_title);

        $response = $apiClient->executeRequest($start_pos, $googlehost, $language);
        $response = Json::decode($response);

        for ($i=0; $i<10; $i++) {
            if (substr($response['items'][$i]['link'], 0, strlen($project_link)) == $project_link){
                $project_pos = $i + 1 + $start_pos;
                break;
            }
        }


        return $project_pos;
    }
}

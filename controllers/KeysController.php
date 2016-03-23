<?php

namespace app\controllers;

use app\models\GroupKey;
use app\models\Groups;
use app\models\ProjectGroup;
use app\models\Projects;
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
use DateTime;
use yii\filters\AccessControl;
use app\components\Additional;


/**
 * KeysController implements the CRUD actions for Keys model.
 */
class KeysController extends Controller
{
    public $layout = '@app/views/layouts/main-admin.php';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'excel-group', 'excel-key', 'pdf-key'],
                        'allow' => true,
                        'roles' => ['seo'],
                    ],
                    [
                        'actions' => ['index', 'view', 'excel-group', 'excel-key', 'pdf-key'],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                    [
                        'actions' => ['scheduled'],
                        'allow' => true,
                        'roles' => ['?'],
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
        if($periodForKeysFrom = Yii::$app->getRequest()->post('periodForKeysFrom')) {
            $periodForKeysFrom = DateTime::createFromFormat('Y-m-d', $periodForKeysFrom)->format('dmY');
        }
        if($periodForKeysTill = Yii::$app->getRequest()->post('periodForKeysTill')) {
            $periodForKeysTill = DateTime::createFromFormat('Y-m-d', $periodForKeysTill)->format('dmY');
        }
        if($periodForKeysFrom == '')
            $periodForKeysFrom = null;
        if($periodForKeysTill == '')
            $periodForKeysTill = null;

        return $this->render('view', [
            'model' => $this->findModel($id),
            'periodForKeysFrom' => $periodForKeysFrom,
            'periodForKeysTill' => $periodForKeysTill,

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


    /**
     * Updates all key items positions of the defined group.
     * @param integer $group_id
     *
     */
    public function actionUpdateAllKeys(){
        $request = Yii::$app->request->get();
        $group_id = $request['group_id'];
//        $project_id
//        $project_link
    //        $group_id
        //        $key_id
        //        $key_title

        $project_id = ProjectGroup::find()->where(['group_id' => $group_id])->one()['project_id'];
        $project_link = Projects::find()->where(['id' => $project_id])->one()['title'];
        /// all keys of the group
        $keys = GroupKey::find()->where(['group_id' => $group_id])->all();

        foreach($keys as $key){
            $key_title = Keys::find()->where(['id' => $key->key_id])->one()['title'];

            $this->actionPlace($project_id, $project_link, $group_id, $key_title, $key->key_id);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Updates single key item position of the defined group.
     *
     */
    public function actionUpdateSingleKey(){
        $request = Yii::$app->request->get();
        $project_link = $request['project_link'];
        $key_id = $request['key_id'];
        $key_title = Keys::find()->where(['id' => $key_id])->one()->title;
        $group_id = $request['group_id'];
        $project_id = ProjectGroup::find()->where(['group_id' => $group_id])->one()->project_id;
        $this->actionPlace($project_id, $project_link, $group_id, $key_title, $key_id);
        return $this->redirect(Yii::$app->request->referrer);


    }


    /**
     * Finds the Keys model key position value.
     *
     */
    public function actionPlace($project_id, $project_link, $group_id, $key_title, $key_id){


        global $project_position;

//        $request = Yii::$app->request->get();
//        $project_link = $request['project_link'];
//        $key_id = $request['key_id'];
//        $key_title = Keys::find()->where(['id' => $key_id])->one()->title;
//        $group_id = $request['group_id'];
//        $project_id = ProjectGroup::find()->where(['group_id' => $group_id])->one()->project_id;

//        echo '<pre>';
//        var_dump($project_id);
//        var_dump($project_link);
//        var_dump($group_id);
//        var_dump($key_title);
//        var_dump($key_id);


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
            (new KeyPosition([
                'key_id' => $key_id,
                'position' => $project_position,
            ]))->save();
        }
//
//        return $this->redirect(Yii::$app->request->referrer);
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

    /**
     * Generates xls for export for all the key items positions of the defined group.
     *
     */
    public function actionExcelGroup()
    {
        $request = Yii::$app->request->get();
        $group_id = $request['group_id'];
        if(isset($request['periodForKeysFrom'])) {
            $periodForKeysFrom = $request['periodForKeysFrom'];
            $periodForKeysFrom = DateTime::createFromFormat("dmY", $periodForKeysFrom)->getTimestamp();
        }
        if(isset($request['periodForKeysTill'])) {
            $periodForKeysTill = $request['periodForKeysTill'];
            $periodForKeysTill = DateTime::createFromFormat("dmY", $periodForKeysTill)->getTimestamp();
        }
        $keys = GroupKey::find()->where(['group_id' => $group_id])->all();
        $items=[];
        foreach($keys as $key){
            array_push($items, $key->id);
        }

        $model = KeyPosition::find()->where(['key_id' => $items])->all();

        if(isset($periodForKeysFrom)){
            $model = KeyPosition::find()->where(['key_id' => $items])
                ->andFilterWhere(['>=', 'date', $periodForKeysFrom])->all();
        }

        if(isset($periodForKeysTill)){
            $model = KeyPosition::find()->where(['key_id' => $items])
                ->andFilterWhere(['<=', 'date', $periodForKeysTill])->all();
        }

        if(isset($periodForKeysFrom) and isset($periodForKeysTill)){
            $model = KeyPosition::find()->where(['key_id' => $items])
                ->andFilterWhere(['between', 'date', $periodForKeysFrom, $periodForKeysTill])->all();
        }

        return $this->render('excel', [
            'model' => $model,
        ]);
    }

    /**
     * Generates xls for export for singe key item positions of the defined group.
     *
     */

    public function actionExcelKey()
    {
        $request = Yii::$app->request->get();
        $key_id = $request['key_id'];

        $model = KeyPosition::find()->where(['key_id' => $key_id])->orderBy('date DESC')->all();

        if(isset($request['periodForKeysFrom'])) {
            $periodForKeysFrom = $request['periodForKeysFrom'];
            $periodForKeysFrom = DateTime::createFromFormat("dmY", $periodForKeysFrom)->getTimestamp();
        }
        if(isset($request['periodForKeysTill'])) {
            $periodForKeysTill = $request['periodForKeysTill'];
            $periodForKeysTill = DateTime::createFromFormat("dmY", $periodForKeysTill)->getTimestamp();
        }

        if(isset($periodForKeysFrom)){
            $model = KeyPosition::find()->where(['key_id' => $key_id])
                ->andFilterWhere(['>=', 'date', $periodForKeysFrom])->all();
        }

        if(isset($periodForKeysTill)){
            $model = KeyPosition::find()->where(['key_id' => $key_id])
                ->andFilterWhere(['<=', 'date', $periodForKeysTill])->all();
        }

        if(isset($periodForKeysFrom) and isset($periodForKeysTill)){
            $model = KeyPosition::find()->where(['key_id' => $key_id])
                ->andFilterWhere(['between', 'date', $periodForKeysFrom, $periodForKeysTill])->all();
        }

        return $this->render('excel', [
            'model' => $model,
        ]);
    }

    /**
     * Updates all key items positions of the defined project in the selected period.
     *
     */
    public function actionScheduled()
    {

        $projects = Projects::find()->all();
        // defining all project ids
        $i=0;
        for($i=0; $i<count($projects); $i++){
            // defining all the project's groups
            $period[$i] = $projects[$i]['upd_period'];
            $project_link = $projects[$i]['title'];
            $project_id[$i] = $projects[$i]['id'];
            $group_ids = ProjectGroup::find()->where(['project_id' => $projects[$i]['id']])->all();
            $n=0;
            for($n=0; $n<count($group_ids); $n++){
                $this->actionCheckGroup($project_id[$i], $project_link, $group_ids[$n]['group_id'], $period[$i]);
            }
        }
    }

    /**
     * Checks the key items of the selected group if update of the key position needed
     *
     */
    public function actionCheckGroup($project_id, $project_link, $group_id, $period){

        $g_k = GroupKey::find()->where(['group_id' => $group_id])->all();
        foreach($g_k as $item){
            // getting all the keys
            $key_pos = KeyPosition::find()->where(['key_id' => $item->key_id])->orderBy('id desc')->one();
            $updated = $key_pos->date + $key_pos->time_from_today;
            // checking the terms
            if((time() - $updated) > $period){
                // getting the distinct key item
                $key = Keys::find()->where(['id' => $item->key_id])->one();
                $this->actionPlace($project_id, $project_link, $group_id, $key['title'], $key['id']);
            }
        }
    }

    public function actionPdfKey(){
        $this->layout = '@app/views/layouts/main-pdf.php';

        $request = Yii::$app->getRequest()->get();
        $key_id = $request['key_id'];

        $model = KeyPosition::find()->where(['key_id' => $key_id])->orderBy('date DESC')->all();

        if(isset($request['periodForKeysFrom'])) {
            $periodForKeysFrom = $request['periodForKeysFrom'];
            $periodForKeysFrom = DateTime::createFromFormat("dmY", $periodForKeysFrom)->getTimestamp();
        }

        if(isset($request['periodForKeysTill'])) {
            $periodForKeysTill = $request['periodForKeysTill'];
            $periodForKeysTill = DateTime::createFromFormat("dmY", $periodForKeysTill)->getTimestamp();
        }

        if(isset($periodForKeysFrom)){
            $model = KeyPosition::find()->where(['key_id' => $key_id])
                ->andFilterWhere(['>=', 'date', $periodForKeysFrom])->all();
        }

        if(isset($periodForKeysTill)){
            $model = KeyPosition::find()->where(['key_id' => $key_id])
                ->andFilterWhere(['<=', 'date', $periodForKeysTill])->all();
        }

        if(isset($periodForKeysFrom) and isset($periodForKeysTill)) {
            $model = KeyPosition::find()->where(['key_id' => $key_id])
                ->andFilterWhere(['between', 'date', $periodForKeysFrom, $periodForKeysTill])->all();
        }

        $content = $this->render('pdf', [

            'model' => $model,
        ]);
        $fileName = 'keys';
        $header = 'Keys list';
        Additional::getPdf($content, $fileName, $header);
    }
}

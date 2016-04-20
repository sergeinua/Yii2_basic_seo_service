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
use DateTime;
use yii\filters\AccessControl;
use app\components\Additional;
use Google_Auth_AssertionCredentials;


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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'excel-group', 'excel-key',
                            'pdf-key', 'update-single-key', 'update-all-keys','google', 'scheduled'],
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
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['admin'],
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
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        // the period is set
        if($period_for_keys_from = Yii::$app->getRequest()->post('period_for_keys_from')) {
            $period_for_keys_from = DateTime::createFromFormat('Y-m-d', $period_for_keys_from)->format('dmY');
        }
        if($period_for_keys_till = Yii::$app->getRequest()->post('period_for_keys_till')) {
            $period_for_keys_till = DateTime::createFromFormat('Y-m-d', $period_for_keys_till)->format('dmY');
        }
        // button pressed with empty field value
        if($period_for_keys_from == '')
            $period_for_keys_from = null;
        if($period_for_keys_till == '')
            $period_for_keys_till = null;

        return $this->render('view', [
            'model' => $this->findModel($id),
            'period_for_keys_from' => $period_for_keys_from,
            'period_for_keys_till' => $period_for_keys_till,
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
                $model->save();
            }

            return $this->redirect(['/groups/view', 'id' => $group_id]);
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
     * @return \yii\web\Response
     */
    public function actionUpdateAllKeys(){
        $request = Yii::$app->request->get();
        $group_id = $request['group_id'];
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
     * @return \yii\web\Response
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
     * @param $project_id
     * @param $project_link
     * @param $group_id
     * @param $key_title
     * @param $key_id
     */
    public function actionPlace($project_id, $project_link, $group_id, $key_title, $key_id){
        $project_position=0;
        $result=0;
        $googlehost = Projects::find()->where(['id' => $project_id])->one()->googlehost;
        $language = Projects::find()->where(['id' => $project_id])->one()->language;
        // getting settings defined in groups
        if(!$googlehost)
            $googlehost = Groups::find()->where(['id' => $group_id])->one()->googlehost;
        if(!$language)
            $language = Groups::find()->where(['id' => $group_id])->one()->language;
        // $start_pos - defining the start position for the google api search
        if($p = KeyPosition::find()->where(['key_id' => $key_id])->orderBy('id DESC')->one()){
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
            if ($result == 0) {
                $result = $this->getDistinctPosition($key_title, $project_link, $start_pos - 10, $googlehost, $language);
            }
            if ($result == 0) {
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

    }

    /**
     * Defines key position for single key
     * @param $key_title
     * @param $project_link
     * @param $start_pos
     * @param $googlehost
     * @param $language
     * @return int
     */
    public function getDistinctPosition($key_title, $project_link, $start_pos, $googlehost, $language)
    {
        $project_pos=0;
        $apiClient = new CustomSearch();
        $apiClient->setApiKey(Yii::$app->params['google_api_search_api_key']);
        $apiClient->setCustomSearchEngineId(Yii::$app->params['google_api_search_custom_search_engine_id']);
        $apiClient->setQuery($key_title);
        $response = $apiClient->executeRequest($start_pos, $googlehost, $language);
        $response = Json::decode($response);
        for ($i=0; $i<10; $i++) {
            if(!isset($response['items'])){
                //if the limit of the updating attempts is exceeded
                break;
                $this->redirect(Yii::$app->request->referrer);
            }
            if(isset($response['items'][$i]['link'])) :
                if (substr($response['items'][$i]['link'], 0, strlen($project_link)) == $project_link){
                    $project_pos = $i + 1 + $start_pos;
                    break;
                }
            endif;
        }

        return $project_pos;
    }

    /**
     * Generates xls for export for all the key items positions of the defined group.
     * @return $this
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function actionExcelGroup()
    {
        $request = Yii::$app->request->get();
        $group_id = $request['group_id'];
        if(isset($request['period_for_keys_from'])) {
            $period_for_keys_from = $request['period_for_keys_from'];
            $period_for_keys_from = DateTime::createFromFormat("dmY", $period_for_keys_from)->getTimestamp();
            $period_for_keys_from = mktime(0,0,0,date('m', $period_for_keys_from), date('d', $period_for_keys_from), date('Y', $period_for_keys_from));
        }
        if(isset($request['period_for_keys_till'])) {
            $period_for_keys_till = $request['period_for_keys_till'];
            $period_for_keys_till = DateTime::createFromFormat("dmY", $period_for_keys_till)->getTimestamp();
            $period_for_keys_till = mktime(23,59,59,date('m', $period_for_keys_till), date('d', $period_for_keys_till), date('Y', $period_for_keys_till));
        }
        $keys = GroupKey::find()->where(['group_id' => $group_id])->all();
        $items=[];
        foreach($keys as $key){
            array_push($items, $key->id);
        }
        $model = KeyPosition::find()->where(['key_id' => $items])->all();
        if(isset($period_for_keys_from)){
            $model = KeyPosition::find()->where(['key_id' => $items])
                ->andFilterWhere(['>=', 'date', $period_for_keys_from])->all();
        }
        if(isset($period_for_keys_till)){
            $model = KeyPosition::find()->where(['key_id' => $items])
                ->andFilterWhere(['<=', 'date', $period_for_keys_till])->all();
        }
        if(isset($period_for_keys_from) and isset($period_for_keys_till)){
            $model = KeyPosition::find()->where(['key_id' => $items])
                ->andFilterWhere(['between', 'date', $period_for_keys_from, $period_for_keys_till])->all();
        }
        $objPHPExcel = new \PHPExcel();
        $sheet=0;
        $objPHPExcel->setActiveSheetIndex($sheet);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->setTitle(Yii::t('app', 'Динамика изменения позиции'))
            ->setCellValue('A1', Yii::t('app', 'Ключевое слово'));
        //defining dates
        if(isset($period_for_keys_from))
            $begin = new \DateTime(date('Y-m-d', $period_for_keys_from));
        else
            $begin = new \DateTime(date('Y-m-d', strtotime('-6 month')));
        if(isset($period_for_keys_till))
            $end = new \DateTime(date('Y-m-d', $period_for_keys_till));
        else
            $end = new \DateTime(date('Y-m-d', strtotime('-1 day')));
        $end = $end->modify( '+1 day' );
        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($begin, $interval ,$end);
        $dates = [];
        $i = 0;
        foreach($daterange as $date) :
            $dates[$i] = $date->format("Y-m-d");
            $i++;
        endforeach;
        $dates = array_reverse($dates);
        for($i=0; $i<count($dates); $i++) {
            $objPHPExcel->getActiveSheet()
                ->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($i+1) . '1', $dates[$i]);
        }
        $highest_col = count($dates);
        $row = 2;
        $keys = [];
        $i = 0;
        foreach($model as $item) :
           $keys[$i] = $item->key_id;
            $i++;
        endforeach;
        $keys = array_unique($keys);
        $keys = array_values($keys);
        for($n=0; $n<count($keys); $n++) {
            $selected_model = KeyPosition::find()->where(['key_id' => $keys[$n]])->all();
            $title = Keys::find()->where(['id' => $selected_model[0]->key_id])->one()->title;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $title);
            foreach ($selected_model as $item) :
                $current_date = date('Y-m-d', $item->date);
                for ($i = 1; $i <= $highest_col; $i++) {
                    $col = \PHPExcel_Cell::stringFromColumnIndex($i);
                    $needed_date = $objPHPExcel->getActiveSheet()->getCell($col . '1')->getValue();
                    if ($current_date == $needed_date) :
                        $objPHPExcel->getActiveSheet()
                            ->setCellValue($col . $row, $item->position);
                    endif;
                }
            endforeach;
            $row++;
        }
        $filename = "MyExcelReport_".date("d-m-Y-His").".xls";
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $fileName = Yii::getAlias('@app/web/download/'.$filename);
        $objWriter->save($fileName);

        return Yii::$app->getResponse()->sendFile($fileName);
    }

    /**
     * Generates xls for export for singe key item positions of the defined group.
     * @return $this
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function actionExcelKey(){
        $request = Yii::$app->request->get();
        $key_id = $request['key_id'];
        $model = KeyPosition::find()->where(['key_id' => $key_id])->orderBy('date DESC')->all();
        if(isset($request['period_for_keys_from'])) {
            $period_for_keys_from = $request['period_for_keys_from'];
            $period_for_keys_from = DateTime::createFromFormat("dmY", $period_for_keys_from)->getTimestamp();
            $period_for_keys_from = mktime(0,0,0,date('m', $period_for_keys_from), date('d', $period_for_keys_from), date('Y', $period_for_keys_from));
        }
        if(isset($request['period_for_keys_till'])) {
            $period_for_keys_till = $request['period_for_keys_till'];
            $period_for_keys_till = DateTime::createFromFormat("dmY", $period_for_keys_till)->getTimestamp();
            $period_for_keys_till = mktime(23,59,59,date('m', $period_for_keys_till), date('d', $period_for_keys_till), date('Y', $period_for_keys_till));
        }
        if(isset($period_for_keys_from)){
            $model = KeyPosition::find()->where(['key_id' => $key_id])
                ->andFilterWhere(['>=', 'date', $period_for_keys_from])->all();
        }
        if(isset($period_for_keys_till)){
            $model = KeyPosition::find()->where(['key_id' => $key_id])
                ->andFilterWhere(['<=', 'date', $period_for_keys_till])->all();
        }
        if(isset($period_for_keys_from) and isset($period_for_keys_till)){
            $model = KeyPosition::find()->where(['key_id' => $key_id])
                ->andFilterWhere(['between', 'date', $period_for_keys_from, $period_for_keys_till])->all();
        }
        $objPHPExcel = new \PHPExcel();
        $sheet=0;
        $objPHPExcel->setActiveSheetIndex($sheet);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->setTitle(Yii::t('app', 'Динамика изменения позиции'))
            ->setCellValue('A1', Yii::t('app', 'Ключевое слово'));
        $i=1;
        foreach($model as $item) :
            $objPHPExcel->getActiveSheet()
                ->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($i).'1', date('Y-m-d', $item->date));
            $i++;
        endforeach;
        $row=2;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$item->title);
        $i=0;
        foreach($model as $item) :
            $objPHPExcel->getActiveSheet()
                ->setCellValue(\PHPExcel_Cell::stringFromColumnIndex($i+1).'2', $item->position);
            $i++;
        endforeach;
        $filename = "MyExcelReport_".date("d-m-Y-His").".xls";
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $fileName = Yii::getAlias('@app/web/download/'.$filename);
        $objWriter->save($fileName);

        return Yii::$app->getResponse()->sendFile($fileName);
    }

    /**
     *Updates all key items positions of the defined project in the selected period.
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
     * @param $project_id
     * @param $project_link
     * @param $group_id
     * @param $period
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

    /**
     * Generates pdf for single key item
     */
    public function actionPdfKey(){
        $this->layout = '@app/views/layouts/main-pdf.php';
        $request = Yii::$app->getRequest()->get();
        $key_id = $request['key_id'];
        $model = KeyPosition::find()->where(['key_id' => $key_id])->orderBy('date DESC')->all();
        if(isset($request['period_for_keys_from'])) {
            $period_for_keys_from = $request['period_for_keys_from'];
            $period_for_keys_from = DateTime::createFromFormat("dmY", $period_for_keys_from)->getTimestamp();
            $period_for_keys_from = mktime(0,0,0,date('m', $period_for_keys_from), date('d', $period_for_keys_from), date('Y', $period_for_keys_from));
        }
        if(isset($request['period_for_keys_till'])) {
            $period_for_keys_till = $request['period_for_keys_till'];
            $period_for_keys_till = DateTime::createFromFormat("dmY", $period_for_keys_till)->getTimestamp();
            $period_for_keys_till = mktime(23,59,59,date('m', $period_for_keys_till), date('d', $period_for_keys_till), date('Y', $period_for_keys_till));
        }
        if(isset($period_for_keys_from)){
            $model = KeyPosition::find()->where(['key_id' => $key_id])
                ->andFilterWhere(['>=', 'date', $period_for_keys_from])
                ->orderBy('date DESC, time_from_today DESC')->all();
        }
        if(isset($period_for_keys_till)){
            $model = KeyPosition::find()->where(['key_id' => $key_id])
                ->andFilterWhere(['<=', 'date', $period_for_keys_till])
                ->orderBy('date DESC, time_from_today DESC')->all();
        }
        if(isset($period_for_keys_from) and isset($period_for_keys_till)) {
            $model = KeyPosition::find()->where(['key_id' => $key_id])
                ->andFilterWhere(['between', 'date', $period_for_keys_from, $period_for_keys_till])
                ->orderBy('date DESC, time_from_today DESC')->all();
        }
        $content = $this->render('pdf', [
            'model' => $model,
        ]);
        $fileName = 'keys';
        $header = 'Keys list';
        Additional::getPdf($content, $fileName, $header);
    }
}

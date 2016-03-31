<?php

namespace app\controllers;

use app\models\ApiCity;
use app\models\CityName;
use app\models\ProjectVisibility;
use Yii;
use app\models\Projects;
use app\models\ProjectsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use DateTime;
use yii\filters\AccessControl;
use app\components\gapi;
use yii\db\Query;

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
        /**
         * Google Analytics data
         * @param object $api_browser
         * @param object $api_source
         * @param object $api_os
         * @param object $api_device
         * @param object $api_users
         * @param object $api_sessions
         * @param object $api_lng
         * @param object $api_country
         * @param object $api_city
         *
         */
        //google analytics api data
        $ga = $this->setGapiParams();
        //total views & browsers
        $api_browser = $this->getApiBrowser($ga);
        //defining source & visits
        $api_source = $this->getApiSource($ga);
        //os
        $api_os = $this->getApiOs($ga);
        //device
        $api_device = $this->getApiDevice($ga);
        //users
        $api_users = $this->getApiUsers($ga);
        // sessions
        $api_sessions = $this->getApiSessions($ga);
        //language
        $api_lng = $this->getApiLanguages($ga);
        //country
        $api_country = $this->getApiCountry($ga);
        //city
        if(Yii::$app->request->get('country'))
            $api_city = $this->actionGetApiCities($ga);

        // none of the periods is defined
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
            'api_browser' => $api_browser,
            'api_source' => $api_source,
            'api_os' => $api_os,
            'api_device' => $api_device,
            'api_users' => $api_users,
            'api_sessions' => $api_sessions,
            'api_lng' => $api_lng,
            'api_country' => $api_country,
            'api_city' => isset($api_city) ? $api_city : null,
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

    /**
     * Finds the quantity of the users, that came from the defined country
     * If period (20 min) exceeds - updates visits
     * If the date range isset - updates ApiCity model
     * @param object $ga
     * @return ApiCity
     */
    public function actionGetApiCities($ga){
        $country = Yii::$app->request->get('country');
        if(ApiCity::find()->one()){
            $model = ApiCity::find()->one();
            if((time() - $model->created_at) > 20 * 60 * 60)
                $this->actionUpdateApiCities($ga);
        } else { // there's no existing data
            $this->actionUpdateApiCities($ga);
        }

        $query = new Query();
        $query->select('city_id, visits')
                ->from('api_city')
                ->where(['country_iso' => $country])
                ->distinct('city_id')
                ->orderBy('visits desc');
        $model = $query->all();

        $i=0;
        foreach($model as $item) :
            $model[$i]['city_id'] = CityName::find()->where(['criteriaId' => $item['city_id']])->one()['name'];
            $i++;
        endforeach;
        return $model;
    }

    /**
     * Updates the quantity of the users, that came from the defined country
     *@param object $ga
     */
    public function actionUpdateApiCities($ga){
        $api_city = $this->getApiCity($ga);
        ApiCity::deleteAll();
        foreach($api_city as $item) :
            $model = new ApiCity();
            $model->city_id = $item->getDimensions()['cityId'];
            $model->country_iso = $item->getDimensions()['countryIsoCode'];
            $model->visits = $item->getMetrics()['visits'];
            $model->created_at = date('U');
            $model->save();
        endforeach;
    }

    /**
     * Updates city & country names
     * source: Google Analytics Api .csv file
     */
    public function actionImportCityNames(){
        CityName::deleteAll();
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        $lines = file(Yii::$app->basePath . '/components/API_Location.csv', FILE_IGNORE_NEW_LINES);
        $csv = [];
        foreach ($lines as $key => $value)
        {
            $csv[$key] = str_getcsv($value);
            $model = new CityName();
            $model->criteriaId = $csv[$key][0];
            $model->name = $csv[$key][1];
            $model->canonicalName = $csv[$key][2];
            $model->parentId = $csv[$key][3];
            $model->countryCode = $csv[$key][4];
            $model->targetType = $csv[$key][5];
            $model->status = $csv[$key][6];
            $model->save();
        }
    }

    /**
     * Sets params for gapi class (Google Analytics Api)
     */
    public function setGapiParams(){
        define('ga_profile_id','86449576');
        return new gapi("356532283258-compute@developer.gserviceaccount.com", Yii::$app->basePath . '/components/Reclamare-fb1d45c039ea.p12');
    }

    public function getApiBrowser($ga){
        return $ga->requestReportData(ga_profile_id,['browser','browserVersion'], ['pageviews','visits']);
    }

    public function getApiSource($ga){
        return $ga->requestReportData(ga_profile_id, ['source'], ['visits']);
    }

    public function getApiOs($ga){
        return $ga->requestReportData(ga_profile_id,['operatingSystem'], ['visits']);
    }

    public function getApiDevice($ga){
        return $ga->requestReportData(ga_profile_id,['mobileDeviceBranding'], ['visits']);
    }

    public function getApiUsers($ga){
        return $ga->requestReportData(ga_profile_id,['sessionCount'],['users', 'newUsers']);
    }

    public function getApiSessions($ga){
        return $ga->requestReportData(ga_profile_id,['sessionDurationBucket'], ['sessionDuration', 'pageviews', 'bounceRate']);
    }

    public function getApiLanguages($ga){
        return $ga->requestReportData(ga_profile_id,['language'], ['visits']);
    }

    public function getApiCountry($ga){
        return $ga->requestReportData(ga_profile_id,['countryIsoCode'], ['visits']);
    }

    public function getApiCity($ga){
        return $ga->requestReportData(ga_profile_id,['cityId', 'countryIsoCode'], ['visits']);
    }
}

<?php

namespace app\controllers;

use app\models\ApiBrowser;
use app\models\ApiCity;
use app\models\ApiCountry;
use app\models\ApiDevice;
use app\models\ApiLng;
use app\models\ApiOs;
use app\models\ApiSessions;
use app\models\ApiSource;
use app\models\ApiUsers;
use app\models\CityName;
use app\models\ProdvigatorData;
use app\models\ProdvigatorOrganic;
use app\models\ProjectVisibility;
use GuzzleHttp\Client;
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
use AdWordsUser;


use BiddingStrategyConfiguration;
use Google\Api\Response\Data\Parser\Exception;

use app\components\ReportDefinition;
use app\components\ReportUtils;
use app\components\Selector;
use app\components\Predicate;
//use app\components\ReportDefinition;
//use app\components\ReportUtils;
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'show-organic',
                            'show-analytics', 'show-prodvigator', 'update-prodvigator', 'update-analytics-data', 'get-adwords-data'],
                        'allow' => true,
                        'roles' => ['seo'],
                    ],
                    [
                        'actions' => ['index', 'view', 'show-analytics', 'show-organic',
                            'show-prodvigator', 'update-prodvigator', 'update-analytics-data', 'get-adwords-data'],
                        'allow' => true,
                        'roles' => ['user'],
                    ],
                    [
                        'actions' => ['get-api-analytics-models', 'client-list'],
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

        return $this->render('view', [
            'model' => $this->findModel($id),
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
     * Sets params for gapi class (Google Analytics Api)
     */
    public function setGapiParams(){
        $project_id = Yii::$app->request->get('project_id');
        $gapi_profile_id = Projects::find()->where(['id' => $project_id])->one()->gapi_profile_id;
        //  add selection profile_id depending on the project_id
        define('ga_profile_id', $gapi_profile_id);
        return new gapi(Yii::$app->params['gapi_google_service_account_email'], Yii::$app->basePath . Yii::$app->params['gapi_p_12_file_path']);
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
        return $ga->requestReportData(ga_profile_id,['sessionDurationBucket'], ['sessionDuration', 'pageviews', 'bounces']);
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


    /**
     * Adds new projects via Google Analytics Api
     * not in use -
     */
    public function actionGetApiAnalyticsModels(){
        $ga = $this->setGapiParams();
        $result = $ga->requestAccountData();
        dump($result);die;
        $exists = null;
        foreach($result as $item) :
            $exists = Projects::find()->where(['title' => $item->getwebsiteUrl() . '/'])->one();
            if(!$exists){
                $model = new Projects();
                $model->title = $item->getwebsiteUrl() . '/';
                $model->status = '1';
                $model->save();
            }
        endforeach;
    }

    /**
     * Displays analytics for a single Projects model.
     * @param integer $id
     * @return mixed
     */
    public function actionShowAnalytics($id){
        /**
         * Google Analytics data
         * @param ApiBrowser $api_browser
         * @param ApiSource $api_source
         * @param ApiOs $api_os
         * @param ApiDevice $api_device
         * @param ApiUsers $api_users
         * @param ApiSessions $api_sessions
         * @param ApiLng $api_lng
         * @param ApiCountry $api_country
         * @param ApiCity $api_city
         */

        $project_id = Yii::$app->request->get('id');

        //total views & browsers
        $api_browser = $this->getBrowserModel($project_id);
        //defining source & visits
        $api_source = $this->getSourceModel($project_id);
        //os
        $api_os = $this->getOsModel($project_id);
        //device
        $api_device = $this->getDeviceModel($project_id);
        //users
        $api_users = $this->getUserModel($project_id);
        // sessions
        $api_sessions = $this->getSessionsModel($project_id);
        //language
        $api_lng = $this->getLngModel($project_id);
        //country
        $api_country = $this->getCountryModel($project_id);
        //city
        if(Yii::$app->request->get('country'))
            $api_city = $this->getCitiesModel($ga, $project_id);
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
        if($periodFrom and $periodTill){
        if($periodTill){
            $project_vis_model = ProjectVisibility::find()->where(['project_id' => $id])->orderBy('date desc')
                ->andFilterWhere(['<=', 'date', $periodTill])->all();
        }
        //periods from & till are defined
            $project_vis_model = ProjectVisibility::find()->where(['project_id' => $id])->orderBy('date desc')
                ->andFilterWhere(['between', 'date', $periodFrom, $periodTill])->all();
        }


        return $this->render('analytics', [
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

    public function actionShowProdvigator($project_id){

        $project_title = Projects::find()->where(['id' => $project_id])->one();
        $project_title = $project_title->title;
        /** date by default is date() - half year */
        $default_date_from = date('Y-m-d', strtotime('-6 months'));
        $model = ProdvigatorData::find()
            ->where(['domain' => $project_title])
            ->andFilterWhere(['>', 'date', $default_date_from])
            ->orderBy('date desc')
            ->all();
        $model_organic = ProdvigatorOrganic::find()
            ->where(['domain' => $project_title])
            ->andFilterWhere(['>', 'date', $default_date_from])
            ->orderBy('date desc')
            ->orderBy('position asc')
            ->all();
        /** dateFrom isset */
        if($dateFrom = Yii::$app->request->post('dateFrom')) {
            $model = ProdvigatorData::find()
                ->where(['domain' => $project_title])
                ->andFilterWhere(['>=', 'date', $dateFrom])
                ->orderBy('date desc')
                ->all();
            $model_organic = ProdvigatorOrganic::find()->where(['domain' => $project_title])
                ->andFilterWhere(['>=', 'date', $dateFrom])
                ->orderBy('date desc')
                ->all();
        }
        /** dateTill isset */
        if($dateTill = Yii::$app->request->post('dateTill')) {
            $model = ProdvigatorData::find()
                ->where(['domain' => $project_title])
                ->andFilterWhere(['>=', 'date', $dateTill])
                ->orderBy('date desc')
                ->all();
            $model_organic = ProdvigatorOrganic::find()->where(['domain' => $project_title])
                ->andFilterWhere(['>=', 'date', $dateTill])
                ->orderBy('date desc')
                ->all();
        }
        /** dateFrom & dateTill isset */
        if(($dateFrom = Yii::$app->request->post('dateFrom')) and ($dateTill = Yii::$app->request->post('dateTill'))) {
            $model = ProdvigatorData::find()
                ->where(['domain' => $project_title])
                ->andFilterWhere(['between', 'date', $dateFrom, $dateTill])
                ->orderBy('date desc')
                ->all();
            $model_organic = ProdvigatorOrganic::find()
                ->where(['domain' => $project_title])
                ->andFilterWhere(['between', 'date', $dateFrom, $dateTill])
                ->orderBy('date desc')
                ->all();
        }

        return $this->render('prodvigator', [
            'model' => $model,
            'model_organic' => $model_organic,
        ]);
    }


    public function actionUpdateProdvigator($project_id){
        $token = Yii::$app->params['prodvigator_token'];
        $domain = Projects::find()->where(['id' => $project_id])->one()->title;
        $project_title = Projects::find()->where(['id' => $project_id])->one();
        $project_title = $project_title->title;

        $url = 'http://api.prodvigator.ru/v3/domain_history?query=' . $domain . '&token=' . $token;
        $result = json_decode(file_get_contents($url));
        ProdvigatorData::deleteAll(['domain' => $domain]);
        foreach($result->result as $item) :
            $model = new ProdvigatorData();
            $model->id = md5($project_id . $item->date);
            $model->domain = $project_title;
            $model->keywords = $item->keywords;
            $model->traff = $item->traff;
            $model->new_keywords = $item->new_keywords;
            $model->out_keywords = $item->out_keywords;
            $model->rised_keywords = $item->rised_keywords;
            $model->down_keywords = $item->down_keywords;
            $model->visible = $item->visible;
            $model->cost_min = $item->cost_min;
            $model->cost_max = $item->cost_max;
            $model->ad_keywords = $item->ad_keywords;
            $model->ads = $item->ads;
            $model->date = $item->date;
            $model->modified_at = date('U');
            $model->save();
        endforeach;
        // ProdvigatorOrganic
        $url = 'http://api.prodvigator.ru/v3/domain_keywords?query=' . $domain . '&token=' . $token . '&page_size=1000';
        $result = json_decode(file_get_contents($url));
        ProdvigatorOrganic::deleteAll(['domain' => $project_title]);
        $cnt = ceil($result->result->total / 1000);

        for ($i=0; $i<$cnt; $i++){
            if($i == 0)
                $url = 'http://api.prodvigator.ru/v3/domain_keywords?query=' . $domain . '&token=' . $token . '&page_size=1000';
            else
                $url = 'http://api.prodvigator.ru/v3/domain_keywords?query=' . $domain . '&token=' . $token . '&page_size=1000&page=' . ($i+1);

            $result = json_decode(file_get_contents($url));
            foreach($result->result->hits as $item) :
                $model = new ProdvigatorOrganic();
                $model->region_queries_count = $item->region_queries_count;
                $model->domain = $domain;
                $model->keyword = $item->keyword;
                $model->url = $item->url;
                $model->right_spell = $item->right_spell;
                $model->dynamic = $item->dynamic;
                $model->found_results = $item->found_results;
                $model->url_crc = $item->url_crc;
                $model->cost = $item->cost;
                $model->concurrency = $item->concurrency;
                $model->position = $item->position;
                $model->date = $item->date;
                $model->keyword_id = $item->keyword_id;
                $model->subdomain = $item->subdomain;
//                $model->region_queries_count_wide = $item->region_queries_count_wide ? $item->region_queries_count_wide : null;
                $model->modified_at = date('U');
                $model->save(false);
            endforeach;
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function updateApiBrowser($api_browser, $project_id){
        ApiBrowser::deleteAll(['project_id' => $project_id]);
        foreach($api_browser as $item) :
            $model = new ApiBrowser();
            $model->pageviews = $item->getMetrics()['pageviews'];
            $model->visits = $item->getMetrics()['visits'];
            $model->browser = $item->getDimensions()['browser'];
            $model->browserVersion = $item->getDimensions()['browserVersion'];
            $model->date = date('U');
            $model->project_id = $project_id;
            $model->save();
        endforeach;
    }

    public function getBrowserModel($project_id){
        return ApiBrowser::find()
            ->where(['project_id' => $project_id])
            ->orderBy('visits desc')
            ->all();
    }

    public function updateApiSource($api_source, $project_id){
        ApiSource::deleteAll(['project_id' => $project_id]);
        foreach($api_source as $item) :
            $model = new ApiSource();
            $model->visits = $item->getMetrics()['visits'];
            $model->source = $item->getDimensions()['source'];
            $model->project_id = $project_id;
            $model->date = date('U');
            $model->save();
        endforeach;
    }

    public function getSourceModel($project_id){
        return ApiSource::find()
            ->where(['project_id' => $project_id])
            ->orderBy('visits desc')
            ->all();
    }

    public function updateApiOs($api_os, $project_id){
        ApiOs::deleteAll(['project_id' => $project_id]);
        foreach($api_os as $item) :
            $model = new ApiOs();
            $model->visits = $item->getMetrics()['visits'];
            $model->os = $item->getDimensions()['operatingSystem'];
            $model->project_id = $project_id;
            $model->date = date('U');
            $model->save();
        endforeach;
    }

    public function getOsModel($project_id){
        return ApiOs::find()
            ->where(['project_id' => $project_id])
            ->orderBy('visits desc')
            ->all();
    }

    public function updateApiDevice($api_device, $project_id){
        ApiDevice::deleteAll(['project_id' => $project_id]);
        foreach($api_device as $item) :
            $model = new ApiDevice();
            $model->visits = $item->getMetrics()['visits'];
            $model->brand = $item->getDimensions()['mobileDeviceBranding'];
            $model->project_id = $project_id;
            $model->date = date('U');
            $model->save();
        endforeach;
    }

    public function getDeviceModel($project_id){
        return ApiDevice::find()
            ->where(['project_id' => $project_id])
            ->orderBy('visits desc')
            ->all();
    }

    public function updateApiUsers($api_users, $project_id){
        ApiUsers::deleteAll(['project_id' => $project_id]);
        foreach($api_users as $item) :
            $model = new ApiUsers();
            $model->users = $item->getMetrics()['users'];
            $model->new_users = $item->getMetrics()['newUsers'];
            $model->session_count = $item->getDimensions()['sessionCount'];
            $model->project_id = $project_id;
            $model->date = date('U');
            $model->save();
        endforeach;
    }

    public function getUserModel($project_id){
        return ApiUsers::find()->where(['project_id' => $project_id])->all();
    }

    public function updateApiSessions($api_sessions, $project_id){
        ApiSessions::deleteAll(['project_id' => $project_id]);
        foreach($api_sessions as $item) :
            $model = new ApiSessions();
            $model->session_duration = $item->getMetrics()['sessionDuration'];
            $model->pageviews = $item->getMetrics()['pageviews'];
            $model->bounces = $item->getMetrics()['bounces'];
            $model->project_id = $project_id;
            $model->session_duration_bucket = $item->getDimensions()['sessionDurationBucket'];
            $model->date = date('U');
            $model->save(false);
        endforeach;
    }

    public function getSessionsModel($project_id){
        return ApiSessions::find()->where(['project_id' => $project_id])->all();
    }

    public function updateApiLanguages($api_lng, $project_id){
        ApiLng::deleteAll(['project_id' => $project_id]);
        foreach($api_lng as $item) :
            $model = new ApiLng();
            $model->visits = $item->getMetrics()['visits'];
            $model->language = $item->getDimensions()['language'];
            $model->project_id = $project_id;
            $model->date = date('U');
            $model->save();
        endforeach;
    }

    public function getLngModel($project_id){
        return ApiLng::find()
            ->where(['project_id' => $project_id])
            ->orderBy('visits desc')
            ->all();
    }

    public function updateApiCountry($api_country, $project_id){
        ApiCountry::deleteAll(['project_id' => $project_id]);
        foreach($api_country as $item) :
            $model = new ApiCountry();
            $model->visits = $item->getMetrics()['visits'];
            $model->country_iso = $item->getDimensions()['countryIsoCode'];
            $model->project_id = $project_id;
            $model-> date = date('U');
            $model->save();
        endforeach;
    }

    public function getCountryModel($project_id){
        return ApiCountry::find()
            ->where(['project_id' => $project_id])
            ->orderBy('visits desc')
            ->all();
    }


    /**
     * Finds the quantity of the users, that came from the defined country
     * If period (20 min) exceeds - updates visits
     * If the date range isset - updates ApiCity model
     * @param object $ga
     * @return ApiCity
     */
    public function getCitiesModel($ga, $project_id){
        $country = Yii::$app->request->get('country');
        $query = new Query();
        $query->select('city_id, visits')
            ->from('api_city')
            ->where(['country_iso' => $country])
            ->andFilterWhere(['project_id' => $project_id])
            ->distinct('city_id')
            ->orderBy('visits desc');
        $model = $query->all();

        $i = 0;
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
    public function updateApiCities($api_city, $project_id){
        ApiCity::deleteAll(['project_id' => $project_id]);
        foreach($api_city as $item) :
            $model = new ApiCity();
            $model->city_id = $item->getDimensions()['cityId'];
            $model->country_iso = $item->getDimensions()['countryIsoCode'];
            $model->visits = $item->getMetrics()['visits'];
            $model->created_at = date('U');
            $model->project_id = $project_id;
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
        $lines = file(Yii::$app->basePath . Yii::$app->params['gapi_city_names_import_file_path'], FILE_IGNORE_NEW_LINES);
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
     * Updates all analytics data
     * using gapi
     */
    public function actionUpdateAnalyticsData($project_id){
        $ga = $this->setGapiParams();
        $this->updateApiBrowser($this->getApiBrowser($ga), $project_id);
        $this->updateApiSource($this->getApiSource($ga), $project_id);
        $this->updateApiOs($this->getApiOs($ga), $project_id);
        $this->updateApiDevice($this->getApiDevice($ga), $project_id);
        $this->updateApiUsers($this->getApiUsers($ga), $project_id);
        $this->updateApiSessions($this->getApiSessions($ga), $project_id);
        $this->updateApiLanguages($this->getApiLanguages($ga), $project_id);
        $this->updateApiCountry($this->getApiCountry($ga), $project_id);
        $this->updateApiCities($this->getApiCity($ga), $project_id);
        Yii::$app->runAction('project-visibility/update-position', ['project_id' => $project_id]);
        return $this->redirect(Yii::$app->request->referrer);
    }






    function getService()
    {
        // Creates and returns the Analytics service object.

        // Load the Google API PHP Client Library.
//        require_once 'google-api-php-client/src/Google/autoload.php';

        // Use the developers console and replace the values with your
        // service account email, and relative location of your key file.
        $service_account_email = Yii::$app->params['gapi_google_service_account_email'];
        $key_file_location = Yii::$app->basePath . Yii::$app->params['gapi_p_12_file_path'];

        // Create and configure a new client object.
        $client = new \Google_Client();

        $client->setApplicationName("HelloAnalytics");
        $analytics = new \Google_Service_Analytics($client);


//        $accounts = $analytics->management_accounts->listManagementAccounts();
//        dump($accounts);die;

        // Read the generated client_secrets.p12 key.
//        $key = file_get_contents($key_file_location);
//        dump(file_get_contents(Yii::$app->basePath . '/components/client_secret_356532283258-004ri2qnpg2ibcc485gricc4o8jaaicg.apps.googleusercontent.com.json'));die;
//        $client->setAuthConfig(Yii::$app->basePath . '/components/client_secret_356532283258-004ri2qnpg2ibcc485gricc4o8jaaicg.apps.googleusercontent.com.json');
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . Yii::$app->basePath . '/components/client_secret_356532283258-004ri2qnpg2ibcc485gricc4o8jaaicg.apps.googleusercontent.com.json');
        $client->useApplicationDefaultCredentials();

//        $client->setAssertionCredentials($cred);
        if($client->isAccessTokenExpired()) {
            $client->refreshTokenWithAssertion();
        }

        return $analytics;
    }

    function getFirstprofileId(&$analytics) {
        // Get the user's first view (profile) ID.

        // Get the list of accounts for the authorized user.
        $accounts = $analytics->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            // Get the list of properties for the authorized user.
            $properties = $analytics->management_webproperties
                ->listManagementWebproperties($firstAccountId);

            if (count($properties->getItems()) > 0) {
                $items = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

                // Get the list of views (profiles) for the authorized user.
                $profiles = $analytics->management_profiles
                    ->listManagementProfiles($firstAccountId, $firstPropertyId);

                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();

                    // Return the first view (profile) ID.
                    return $items[0]->getId();

                } else {
                    throw new Exception('No views (profiles) found for this user.');
                }
            } else {
                throw new Exception('No properties found for this user.');
            }
        } else {
            throw new Exception('No accounts found for this user.');
        }
    }

    function getResults(&$analytics, $profileId) {
        // Calls the Core Reporting API and queries for the number of sessions
        // for the last seven days.
        return $analytics->data_ga->get(
            'ga:' . $profileId,
            '7daysAgo',
            'today',
            'ga:sessions');
    }

    function printResults(&$results) {
        // Parses the response from the Core Reporting API and prints
        // the profile name and total sessions.
        if (count($results->getRows()) > 0) {

            // Get the profile name.
            $profileName = $results->getProfileInfo()->getProfileName();

            // Get the entry for the first entry in the first row.
            $rows = $results->getRows();
            $sessions = $rows[0][0];

            // Print the results.
            print "First view (profile) found: $profileName\n";
            print "Total sessions: $sessions\n";
        } else {
            print "No results found.\n";
        }
    }

    //TODO: not finished yet
    public function actionClientList()
    {
        $ga = $this->setGapiParams();
        dump($ga->requestAccountData());die;
        dump($ga->getAccounts());die;




        $analytics = $this->getService();
//        $profile = $this->getFirstProfileId($analytics);
//        $results = $this->getResults($analytics, $profile);
//        printResults($results);

        $profiles = $analytics->management_profiles
            ->listManagementProfiles('86449576', '~all');
        dump($profiles);die;
//        GET https://www.googleapis.com/analytics/v3/management/accounts?key={YOUR_API_KEY}
//        $curl = curl_init();
//        curl_setopt_array($curl, array(
//            CURLOPT_RETURNTRANSFER => 1,
//            CURLOPT_URL => 'https://www.googleapis.com/analytics/v3/management/accounts?key=AIzaSyBfA8r3D1hy11k7bdGQrXrMiptZ5MaMnSE'
//        ));
//        $result = curl_exec($curl);
//        dump($result);die;
//
    }







    function DownloadCriteriaReportExample(AdWordsUser $user, $filePath) {

        // Load the service, so that the required classes are available.
        $user->LoadService('ReportDefinitionService', 'v201603');
        // Optional: Set clientCustomerId to get reports of your child accounts
        // $user->SetClientCustomerId('INSERT_CLIENT_CUSTOMER_ID_HERE');

        // Create selector.
        $selector = new Selector();
        $selector->fields = array('CampaignId', 'AdGroupId', 'Id', 'Criteria',
            'CriteriaType', 'Impressions', 'Clicks', 'Cost');

        // Optional: use predicate to filter out paused criteria.
        $selector->predicates[] = new Predicate('Status', 'NOT_IN', array('PAUSED'));

        // Create report definition.
        $reportDefinition = new ReportDefinition();
        $reportDefinition->selector = $selector;
        $reportDefinition->reportName = 'Criteria performance report #' . uniqid();
        $reportDefinition->dateRangeType = 'LAST_7_DAYS';
        $reportDefinition->reportType = 'CRITERIA_PERFORMANCE_REPORT';
        $reportDefinition->downloadFormat = 'CSV';

        // Set additional options.
        $options = array('version' => 'v201603');

        // Optional: Set skipReportHeader, skipColumnHeader, skipReportSummary to
        //     suppress headers or summary rows.
        // $options['skipReportHeader'] = true;
        // $options['skipColumnHeader'] = true;
        // $options['skipReportSummary'] = true;
        // Optional: Set includeZeroImpressions to include zero impression rows in
        //     the report output.
        // $options['includeZeroImpressions'] = true;

        // Download report.
        ReportUtils::DownloadReport($reportDefinition, $filePath, $user, $options);
        printf("Report with name '%s' was downloaded to '%s'.\n",
            $reportDefinition->reportName, $filePath);
    }





    public function actionGetAdwordsData(){
        $filePath = Yii::$app->basePath . 'web/download/report.csv';

        require '../vendor/googleads/googleads-php-lib/examples/AdWords/Auth/GetRefreshToken.php';


        // Don't run the example if the file is being included.


        try {
            // Get the client ID and secret from the auth.ini file. If you do not have a
            // client ID or secret, please create one of type "installed application" in
            // the Google API console: https://code.google.com/apis/console#access
            // and set it in the auth.ini file.
            $user = new AdWordsUser();
            $user->LogAll();

            // Get the OAuth2 credential.
            $oauth2Info = $this->GetOAuth2Credential($user);
            // Enter the refresh token into your auth.ini file.
            printf("Your refresh token is: %s\n\n", $oauth2Info['refresh_token']);
            printf("In your auth.ini file, edit the refresh_token line to be:\n"
                . "refresh_token = \"%s\"\n", $oauth2Info['refresh_token']);
        } catch (\OAuth2Exception $e) {
            \ExampleUtils::CheckForOAuth2Errors($e);
        } catch (\ValidationException $e) {
            \ExampleUtils::CheckForOAuth2Errors($e);
        } catch (\Exception $e) {
            printf("An error has occurred: %s\n", $e->getMessage());
        }

    }




    public function actionShowAdwords(){

        return $this->render('adwords');
    }

    function GetOAuth2Credential($user) {
        $redirectUri = null;
        $offline = true;
        // Get the authorization URL for the OAuth2 token.
        // No redirect URL is being used since this is an installed application. A web
        // application would pass in a redirect URL back to the application,
        // ensuring it's one that has been configured in the API console.
        // Passing true for the second parameter ($offline) will provide us a refresh
        // token which can used be refresh the access token when it expires.
        $OAuth2Handler = $user->GetOAuth2Handler();
        $authorizationUrl = $OAuth2Handler->GetAuthorizationUrl(
            $user->GetOAuth2Info(), $redirectUri, $offline);
        // In a web application you would redirect the user to the authorization URL
        // and after approving the token they would be redirected back to the
        // redirect URL, with the URL parameter "code" added. For desktop
        // or server applications, spawn a browser to the URL and then have the user
        // enter the authorization code that is displayed.
        printf("Log in to your AdWords account and open the following URL:\n%s\n\n",
            $authorizationUrl);
        print "After approving the token enter the authorization code here: ";
        $stdin = fopen('php://stdin', 'r');
        $code = trim(fgets($stdin));
        fclose($stdin);
        print "\n";
        // Get the access token using the authorization code. Ensure you use the same
        // redirect URL used when requesting authorization.
        $user->SetOAuth2Info(
            $OAuth2Handler->GetAccessToken(
                $user->GetOAuth2Info(), $code, $redirectUri));
        // The access token expires but the refresh token obtained for offline use
        // doesn't, and should be stored for later use.
        return $user->GetOAuth2Info();
    }

}

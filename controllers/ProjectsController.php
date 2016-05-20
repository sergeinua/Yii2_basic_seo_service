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
use app\models\ProdvigatorOrganicSearch;
use app\models\ProjectUser;
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
use AdWordsUser;
use BiddingStrategyConfiguration;
use Google\Api\Response\Data\Parser\Exception;
use app\components\ReportDefinition;
use app\components\ReportUtils;
use app\components\Selector;
use app\components\Predicate;
use yii\data\ActiveDataProvider;

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
        $user_id = Yii::$app->user->identity->id;
        $project_list = ProjectUser::find()->where(['user_id' => $user_id])->all();

        return $this->render('index', [
            'searchModel' => count($project_list) > 0 ? $searchModel : null,
            'dataProvider' => count($project_list) > 0 ? $dataProvider : null,
        ]);
    }

    /**
     * Displays a single Projects model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        //admin is able to see all projects
        if(Yii::$app->user->identity->role != 'admin') :
            //checking if user is defined for the selected project
            $user_id = Yii::$app->user->id;
            $allowed = ProjectUser::find()->where(['user_id' => $user_id])->exists();
            if(!$allowed)
                return $this->redirect(Yii::$app->urlManager->createUrl('projects/index'));
        endif;
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
     * @return gapi
     */
    public function setGapiParams(){
        $project_id = Yii::$app->request->get('project_id');
        $gapi_profile_id = Projects::find()->where(['id' => $project_id])->one()->gapi_profile_id;
        //  add selection profile_id depending on the project_id
        define('ga_profile_id', $gapi_profile_id);

        return new gapi(Yii::$app->params['gapi_google_service_account_email'], Yii::$app->basePath . Yii::$app->params['gapi_p_12_file_path']);
    }

    /**
     * Defines settings for google analytics: browser data
     * @param $ga
     * @return mixed
     */
    public function getApiBrowser($ga){

        return $ga->requestReportData(ga_profile_id,['browser','browserVersion'], ['pageviews','visits']);
    }

    /**
     * Defines settings for google analytics: source data
     * @param $ga
     * @return mixed
     */
    public function getApiSource($ga){

        return $ga->requestReportData(ga_profile_id, ['source'], ['visits']);
    }

    /**
     * Defines settings for google analytics: os data
     * @param $ga
     * @return mixed
     */
    public function getApiOs($ga){

        return $ga->requestReportData(ga_profile_id,['operatingSystem'], ['visits']);
    }

    /**
     * Defines settings for google analytics: device data
     * @param $ga
     * @return mixed
     */
    public function getApiDevice($ga){

        return $ga->requestReportData(ga_profile_id,['mobileDeviceBranding'], ['visits']);
    }

    /**
     * Defines settings for google analytics: user data
     * @param $ga
     * @return mixed
     */
    public function getApiUsers($ga){

        return $ga->requestReportData(ga_profile_id,['sessionCount'],['users', 'newUsers']);
    }

    /**
     * Defines settings for google analytics: session data
     * @param $ga
     * @return mixed
     */
    public function getApiSessions($ga){

        return $ga->requestReportData(ga_profile_id,['sessionDurationBucket'], ['sessionDuration', 'pageviews', 'bounces']);
    }

    /**
     * Defines settings for google analytics: language data
     * @param $ga
     * @return mixed
     */
    public function getApiLanguages($ga){

        return $ga->requestReportData(ga_profile_id,['language'], ['visits']);
    }

    /**
     * Defines settings for google analytics: country data
     * @param $ga
     * @return mixed
     */
    public function getApiCountry($ga){

        return $ga->requestReportData(ga_profile_id,['countryIsoCode'], ['visits']);
    }

    /**
     * Defines settings for google analytics: city data
     * @param $ga
     * @return mixed
     */
    public function getApiCity($ga){

        return $ga->requestReportData(ga_profile_id,['cityId', 'countryIsoCode'], ['visits']);
    }

    /**
     * Adds new projects via Google Analytics Api
     * deprecated - Sergei told models would be configured manually
     * left in case he changes his mind )
     * @throws \yii\base\Exception
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
     * @param $id
     * @return string
     * @throws NotFoundHttpException
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
        //city in case the country is defined
        if(Yii::$app->request->get('country'))
            $api_city = $this->getCitiesModel($project_id);
        // none of the periods is defined
        $project_vis_model = ProjectVisibility::find()->where(['project_id' => $id])->orderBy('date desc')->all();

        if($period_from = Yii::$app->getRequest()->post('period_for_project_from'))
            $period_from = DateTime::createFromFormat('Y-m-d', $period_from)->format('dmY');
        if($period_till = Yii::$app->getRequest()->post('period_for_project_till'))
            $period_till = DateTime::createFromFormat('Y-m-d', $period_till)->format('dmY');

        //period from is defined
        if($period_from){
            $project_vis_model = ProjectVisibility::find()->where(['project_id' => $id])->orderBy('date desc')
                ->andFilterWhere(['>=', 'date', $period_from])->all();
        }
        //period till is defined
        if($period_from and $period_till){
        if($period_till){
            $project_vis_model = ProjectVisibility::find()->where(['project_id' => $id])->orderBy('date desc')
                ->andFilterWhere(['<=', 'date', $period_till])->all();
        }
        // both periods from & till are defined
            $project_vis_model = ProjectVisibility::find()->where(['project_id' => $id])->orderBy('date desc')
                ->andFilterWhere(['between', 'date', $period_from, $period_till])->all();
        }

        return $this->render('analytics', [
            'model' => $this->findModel($id),
            'project_vis_model' => $project_vis_model,
            'period_from' => $period_from,
            'period_till' => $period_till,
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
     * Shows data of the prodvigator's api for the defined project
     * @param $project_id
     * @return string
     */
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
        //pagination added
        $searchModel = New ProdvigatorOrganicSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['domain' => $project_title]);
        $dataProvider->pagination->pageSize=50;

        /** date_from isset */
        if($date_from = Yii::$app->request->post('date_from')) {
            $model = ProdvigatorData::find()
                ->where(['domain' => $project_title])
                ->andFilterWhere(['>=', 'date', $date_from])
                ->orderBy('date desc')
                ->all();
            $model_organic = ProdvigatorOrganic::find()->where(['domain' => $project_title])
                ->andFilterWhere(['>=', 'date', $date_from])
                ->orderBy('date desc')
                ->all();
        }
        /** date_till isset */
        if($date_till = Yii::$app->request->post('date_till')) {
            $model = ProdvigatorData::find()
                ->where(['domain' => $project_title])
                ->andFilterWhere(['>=', 'date', $date_till])
                ->orderBy('date desc')
                ->all();
            $model_organic = ProdvigatorOrganic::find()->where(['domain' => $project_title])
                ->andFilterWhere(['>=', 'date', $date_till])
                ->orderBy('date desc')
                ->all();
        }
        /** date_from & date_till isset */
        if(($date_from = Yii::$app->request->post('date_from')) and ($date_till = Yii::$app->request->post('date_till'))) {
            $model = ProdvigatorData::find()
                ->where(['domain' => $project_title])
                ->andFilterWhere(['between', 'date', $date_from, $date_till])
                ->orderBy('date desc')
                ->all();
            $model_organic = ProdvigatorOrganic::find()
                ->where(['domain' => $project_title])
                ->andFilterWhere(['between', 'date', $date_from, $date_till])
                ->orderBy('date desc')
                ->all();
        }

        return $this->render('prodvigator', [
            'model' => $model,
            'model_organic' => $model_organic,
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
        ]);
    }


    /**
     * Updates prodvigator's data for the defined project
     * @param $project_id
     * @return \yii\web\Response
     */
    public function actionUpdateProdvigator($project_id){
        $token = Yii::$app->params['prodvigator_token'];
        $domain = Projects::find()->where(['id' => $project_id])->one()->title;
        $project_title = Projects::find()->where(['id' => $project_id])->one();
        $project_title = $project_title->title;
        // prodvigator request itself - in case modifications are needed
        $url = 'http://api.serpstat.com/v3/domain_history?query=' . $domain . '&token=' . $token;
        $result = json_decode(file_get_contents($url));

        // replacing non actual models
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
            $model->cost_min = isset($item->cost_min) ? $item->cost_min : 0;
            $model->cost_max = isset($item->cost_max) ? $item->cost_max : 0;
            $model->ad_keywords = $item->ad_keywords;
            $model->ads = $item->ads;
            $model->date = $item->date;
            $model->modified_at = date('U');
            $model->save();
        endforeach;
        // prodvigator organic request itself - in case modifications are needed
        $url = 'http://api.serpstat.com/v3/domain_keywords?query=' . $domain . '&token=' . $token . '&se=g_ua&page_size=1000';
        $result = json_decode(file_get_contents($url));
        ProdvigatorOrganic::deleteAll(['domain' => $project_title]);
        $cnt = ceil($result->result->total / 1000);

        for ($i=0; $i<$cnt; $i++){
            if($i == 0)
                $url = 'http://api.serpstat.com/v3/domain_keywords?query=' . $domain . '&token=' . $token . '&se=g_ua&page_size=1000';
            else
                $url = 'http://api.serpstat.com/v3/domain_keywords?query=' . $domain . '&token=' . $token . '&se=g_ua&page_size=1000&page=' . ($i+1);

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
                $model->modified_at = date('U');
                $model->save(false);
            endforeach;
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Updates browser model via Google Analytics api for the defined project
     * @param $api_browser
     * @param $project_id
     */
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

    /**
     * Gets browser model for the defined project
     * @param $project_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getBrowserModel($project_id){
        return ApiBrowser::find()
            ->where(['project_id' => $project_id])
            ->orderBy('visits desc')
            ->all();
    }

    /**
     * Updates source model via Google Analytics api for the defined project
     * @param $api_source
     * @param $project_id
     */
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

    /**
     * Gets source model for the defined project
     * @param $project_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getSourceModel($project_id){
        return ApiSource::find()
            ->where(['project_id' => $project_id])
            ->orderBy('visits desc')
            ->all();
    }

    /**
     * Updates os model via Google Analytics api for the defined project
     * @param $api_os
     * @param $project_id
     */
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

    /**
     * Gets os model for the defined project
     * @param $project_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getOsModel($project_id){
        return ApiOs::find()
            ->where(['project_id' => $project_id])
            ->orderBy('visits desc')
            ->all();
    }

    /**
     * Updates device model via Google Analytics api for the defined project
     * @param $api_device
     * @param $project_id
     */
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

    /**
     * Gets device model for the defined project
     * @param $project_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getDeviceModel($project_id){
        return ApiDevice::find()
            ->where(['project_id' => $project_id])
            ->orderBy('visits desc')
            ->all();
    }

    /**
     * Updates user model via Google Analytics api for the defined project
     * @param $api_users
     * @param $project_id
     */
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

    /**
     * Gets user model for the defined project
     * @param $project_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getUserModel($project_id){
        return ApiUsers::find()->where(['project_id' => $project_id])->all();
    }

    /**
     * Updates session model via Google Analytics api for the defined project
     * @param $api_sessions
     * @param $project_id
     */
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

    /**
     * Gets session model for the defined project
     * @param $project_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getSessionsModel($project_id){
        return ApiSessions::find()->where(['project_id' => $project_id])->all();
    }

    /**
     * Updates language model via Google Analytics api for the defined project
     * @param $api_lng
     * @param $project_id
     */
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

    /**
     * Gets language model for the defined project
     * @param $project_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getLngModel($project_id){
        return ApiLng::find()
            ->where(['project_id' => $project_id])
            ->orderBy('visits desc')
            ->all();
    }

    /**
     * Updates country model via Google Analytics api for the defined project
     * @param $api_country
     * @param $project_id
     */
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

    /**
     * Gets country model for the defined project
     * @param $project_id
     * @return array|\yii\db\ActiveRecord[]
     */
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
     * @param $project_id
     * @return array
     */
    public function getCitiesModel($project_id){
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
     * Updates the quantity of the users, that came from the defined city
     * @param $api_city
     * @param $project_id
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
     * duration: endless
     */
    public function actionImportCityNames(){
        CityName::deleteAll();
        // that's for avoiding errors
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
     * @param $project_id
     * @return \yii\web\Response
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


    /**
     * Google AdWords Api
     * @param AdWordsUser $user
     * @param $filePath
     */
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


    /**
     * Google AdWords Api
     */
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

    /**
     * Getting AdWords model
     * @return string
     */
    public function actionShowAdwords(){

        return $this->render('adwords');
    }

    /**
     * Google AdWords Api authentication
     * @param $user
     * @return mixed
     */
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

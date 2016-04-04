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
use Google_Client;
use Google_Service_Books;
use Google_Service_Analytics;
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
        //if period is set
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
        $project_position=0;
        $result=0;

        $googlehost = Projects::find()->where(['id' => $project_id])->one()->googlehost;
        $language = Projects::find()->where(['id' => $project_id])->one()->language;

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

    public function getDistinctPosition($key_title, $project_link, $start_pos, $googlehost, $language)
    {
        global $project_pos;
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
            $periodForKeysFrom = mktime(0,0,0,date('m', $periodForKeysFrom), date('d', $periodForKeysFrom), date('Y', $periodForKeysFrom));
        }
        if(isset($request['periodForKeysTill'])) {
            $periodForKeysTill = $request['periodForKeysTill'];
            $periodForKeysTill = DateTime::createFromFormat("dmY", $periodForKeysTill)->getTimestamp();
            $periodForKeysTill = mktime(23,59,59,date('m', $periodForKeysTill), date('d', $periodForKeysTill), date('Y', $periodForKeysTill));
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
            $periodForKeysFrom = mktime(0,0,0,date('m', $periodForKeysFrom), date('d', $periodForKeysFrom), date('Y', $periodForKeysFrom));
        }
        if(isset($request['periodForKeysTill'])) {
            $periodForKeysTill = $request['periodForKeysTill'];
            $periodForKeysTill = DateTime::createFromFormat("dmY", $periodForKeysTill)->getTimestamp();
            $periodForKeysTill = mktime(23,59,59,date('m', $periodForKeysTill), date('d', $periodForKeysTill), date('Y', $periodForKeysTill));
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
            $periodForKeysFrom = mktime(0,0,0,date('m', $periodForKeysFrom), date('d', $periodForKeysFrom), date('Y', $periodForKeysFrom));
        }


        if(isset($request['periodForKeysTill'])) {
            $periodForKeysTill = $request['periodForKeysTill'];
            $periodForKeysTill = DateTime::createFromFormat("dmY", $periodForKeysTill)->getTimestamp();
            $periodForKeysTill = mktime(23,59,59,date('m', $periodForKeysTill), date('d', $periodForKeysTill), date('Y', $periodForKeysTill));
        }

        if(isset($periodForKeysFrom)){
            $model = KeyPosition::find()->where(['key_id' => $key_id])
                ->andFilterWhere(['>=', 'date', $periodForKeysFrom])
                ->orderBy('date DESC, time_from_today DESC')->all();
        }

        if(isset($periodForKeysTill)){
            $model = KeyPosition::find()->where(['key_id' => $key_id])
                ->andFilterWhere(['<=', 'date', $periodForKeysTill])
                ->orderBy('date DESC, time_from_today DESC')->all();
        }

        if(isset($periodForKeysFrom) and isset($periodForKeysTill)) {
            $model = KeyPosition::find()->where(['key_id' => $key_id])
                ->andFilterWhere(['between', 'date', $periodForKeysFrom, $periodForKeysTill])
                ->orderBy('date DESC, time_from_today DESC')->all();
        }

        $content = $this->render('pdf', [

            'model' => $model,
        ]);
        $fileName = 'keys';
        $header = 'Keys list';
        Additional::getPdf($content, $fileName, $header);
    }

//    public function actionGoogle_(){
//        $client = new Google_Client();
//        $client->setApplicationName("Client_Library_Examples");
//        $client->setDeveloperKey("AIzaSyAS57f0f1-8ZLL1q8fiutpNs3bTU38zE8I");
//
//        $service = new Google_Service_Analytics($client);
//        //$optParams = array('filter' => 'free-ebooks');
////        $results = $service->volumes->listVolumes('Henry David Thoreau', $optParams);
//        dump($service);
//
////        foreach ($results as $item) {
////            dump($item['volumeInfo']['title']);//, "<br /> \n";
////        }
//        die;
//    }

/*
    function getService()
    {
        // Creates and returns the Analytics service object.

        // Load the Google API PHP Client Library.
//        require_once 'google-api-php-client/src/Google/autoload.php';
//        require_once Yii::$app->basePath . '/vendor/google/apiclient/src/Google/autoload.php';



        // Use the developers console and replace the values with your
        // service account email, and relative location of your key file.
        $service_account_email = '356532283258-compute@developer.gserviceaccount.com';
        $key_file_location = Yii::$app->basePath . '/components/Reclamare-fb1d45c039ea.p12';

        // Create and configure a new client object.
        $client = new Google_Client();
        $client->setApplicationName("SiteAnalytics");
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . Yii::$app->basePath . '/components/client_secret_356532283258-004ri2qnpg2ibcc485gricc4o8jaaicg.apps.googleusercontent.com.json');
        $client->useApplicationDefaultCredentials();
        $client->setScopes(['https://www.googleapis.com/auth/books']);
        dump($client);

        $analytics = new Google_Service_Analytics($client);
        dump($analytics);

        $accessToken = $client->getAccessToken();
        dump($accessToken);

        file_put_contents( Yii::$app->basePath . '/components/client_secret_356532283258-004ri2qnpg2ibcc485gricc4o8jaaicg.apps.googleusercontent.com.json', json_encode($accessToken));

        // Read the generated client_secrets.p12 key.
        $key = file_get_contents($key_file_location);
//        dump($key);
//
////        $cred = new Google_Auth_AssertionCredentials(
//        $cred = $client->setAuthConfig(
//            $service_account_email,
//            array(Google_Service_Analytics::ANALYTICS_READONLY),
//            $key
//        );
        die();
//        $client->setAuthConfig(Yii::$app->basePath . '/components/client_secret_356532283258-004ri2qnpg2ibcc485gricc4o8jaaicg.apps.googleusercontent.com.json');
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . Yii::$app->basePath . '//components/client_secret_356532283258-004ri2qnpg2ibcc485gricc4o8jaaicg.apps.googleusercontent.com.json');
        $client->useApplicationDefaultCredentials();
        $client->setScopes(['https://www.googleapis.com/auth/books']);
        $service = new Google_Service_Books($client);
        $results = $service->volumes->listVolumes('Henry David Thoreau');
        dump($results);


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
    public function actionGoogle()
    {
        $analytics = $this->getService();
        $profile = $this->getFirstProfileId($analytics);
        $results = $this->getResults($analytics, $profile);
        $this->printResults($results);
        die;
    }
*/


///*
//    /**
//     * Get Analytics API object
//     */
//    function getService( $service_account_email, $key ) {
//        // Creates and returns the Analytics service object.
//
//        // Load the Google API PHP Client Library.
//
//
//        // Create and configure a new client object.
//        $client = new Google_Client();
//        $client->setApplicationName( 'Google Analytics Dashboard' );
//        $analytics = new Google_Service_Analytics( $client );
//
//        // Read the generated client_secrets.p12 key.
//        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . Yii::$app->basePath . '/components/client_secret_356532283258-004ri2qnpg2ibcc485gricc4o8jaaicg.apps.googleusercontent.com.json');
//        $client->useApplicationDefaultCredentials();
//
//        if( $client->isAccessTokenExpired() ) {
//            $client->refreshTokenWithAssertion();
//        }
//
//        return $analytics;
//    }
//
//    public function actionGoogle()
//    {
//
//
//        /**
//         * Set Google service account details
//         */
//        $google_account = array(
//            'email'   => '356532283258-compute@developer.gserviceaccount.com',
//            'key'     => file_get_contents( Yii::$app->basePath . '/components/Reclamare-fb1d45c039ea.p12' ),
//            'profile' => '86449576'
//        );
//
//        /**
//         * Get Analytics API instance
//         */
//        $analytics = $this->getService(
//            $google_account['email'],
//            $google_account['key']
//        );
//
//        /**
//         * Query the Analytics data
//         */
//        $results = $analytics->data_ga->get(
//            'ga:' . $google_account['profile'],
//            '30daysAgo',
//            'today',
//            'ga:sessions',
//            array(
//                'dimensions' => 'ga:country',
//                'sort' => '-ga:sessions',
//                'max-results' => 20
//            ));
//        $rows = $results->getRows();
//        var_dump($rows);
//
//    }

//    function getService()
//    {
//        // Creates and returns the Analytics service object.
//        // Load the Google API PHP Client Library.
//
//
//
//        // Create and configure a new client object.
//        $client = new \Google_Client();
//        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . Yii::$app->basePath . '/components/client_secret_356532283258-004ri2qnpg2ibcc485gricc4o8jaaicg.apps.googleusercontent.com.json');
//        $client->useApplicationDefaultCredentials();
//        $client->addScope('https://www.googleapis.com/auth/analytics.readonly');
////        dump(new \Google_Service_Analytics($client));die;
////        dump($client);die;
//        return new \Google_Service_Analytics($client);
//    }
//    function getFirstprofileId(&$analytics) {
////        require Yii::$app->basePath . '/vendor/google/apiclient/src/Google/Service/Analytics.php';
//            // Get the user's first view (profile) ID.
//        // Get the list of accounts for the authorized user.
//
//        $accounts = $analytics->management_accounts->listManagementAccounts();
//        if (count($accounts->getItems()) > 0) {
//            $items = $accounts->getItems();
//            $firstAccountId = $items[0]->getId();
//            // Get the list of properties for the authorized user.
//            $properties = $analytics->management_webproperties
//                ->listManagementWebproperties($firstAccountId);
//            if (count($properties->getItems()) > 0) {
//                $items = $properties->getItems();
//                $firstPropertyId = $items[0]->getId();
//                // Get the list of views (profiles) for the authorized user.
//                $profiles = $analytics->management_profiles
//                    ->listManagementProfiles($firstAccountId, $firstPropertyId);
//                if (count($profiles->getItems()) > 0) {
//                    $items = $profiles->getItems();
//                    // Return the first view (profile) ID.
//                    return $items[0]->getId();
//                } else {
//                    throw new Exception('No views (profiles) found for this user.');
//                }
//            } else {
//                throw new Exception('No properties found for this user.');
//            }
//        } else {
//            throw new Exception('No accounts found for this user.');
//        }
//    }
//    function getResults(&$analytics, $profileId) {
//        // Calls the Core Reporting API and queries for the number of sessions
//        // for the last seven days.
//        return $analytics->data_ga->get(
//            'ga:' . $profileId,
//            '7daysAgo',
//            'today',
//            'ga:sessions');
//    }
//    function printResults(&$results) {
//        // Parses the response from the Core Reporting API and prints
//        // the profile name and total sessions.
//        if (count($results->getRows()) > 0) {
//            // Get the profile name.
//            $profileName = $results->getProfileInfo()->getProfileName();
//            // Get the entry for the first entry in the first row.
//            $rows = $results->getRows();
//            $sessions = $rows[0][0];
//            // Print the results.
//            print "First view (profile) found: $profileName\n";
//            print "Total sessions: $sessions\n";
//        } else {
//            print "No results found.\n";
//        }
//    }
//
//    public function actionGoogle(){
//        $analytics = $this->getService();
//        $profile = $this->getFirstProfileId($analytics);
//        $results = $this->getResults($analytics, $profile);
//        printResults($results);
//    }


}

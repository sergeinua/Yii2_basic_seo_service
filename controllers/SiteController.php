<?php

namespace app\controllers;

use app\commands\RbacController;
use app\components\Google\Api\CustomSearch;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use linslin\yii2\curl;
use yii\web\Response;
use yii\web\ForbiddenHttpException;


class SiteController extends Controller
{
    public $layout = '@app/views/layouts/main-admin.php';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'logout'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'logout'],
                        'allow' => true,
                        'roles' => ['guest'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
/*
        $apiClient = new CustomSearch();
        $apiClient->setApiKey('AIzaSyBfA8r3D1hy11k7bdGQrXrMiptZ5MaMnSE');
        $apiClient->setCustomSearchEngineId('006254468391416147805:-jyqgokuwi8');
        $apiClient->setQuery('сайт под ключ киев');


        $response = $apiClient->executeRequest();
        echo '<meta charset=utf-8>';
        echo '<pre>';

        $response = Json::decode($response);

        for ($i=0;$i<10;$i++) {
            var_dump($response['items'][$i]['link']);
        }
        die();


*/


//        echo '<pre>';
//        print_r(Yii::$app->getUser()->identity);
//        die();
//
//        echo Yii::$app->getUser()->can('user');
//        die();




        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

//    public function beforeAction($action)
//    {
//        if (parent::beforeAction($action)) {
//            if (!\Yii::$app->user->can($action->id)) {
//                throw new ForbiddenHttpException('Access denied');
//            }
//            return true;
//        } else {
//            return false;
//        }
//    }
}

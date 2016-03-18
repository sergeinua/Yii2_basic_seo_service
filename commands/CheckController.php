<?php
namespace app\commands;

use yii\console\Controller;
use Yii;
use app\controllers\KeysController;

Class CheckController extends Controller
{
    public function actionCheck(){

//        Yii::$app->runAction('keys/scheduled');
        $result = file_get_contents('./controllers/KeysController.php');
        echo $result;
    }
}
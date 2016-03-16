<?php

namespace app\controllers;

use app\models\GroupKey;
use app\models\KeyPosition;
use app\models\Keys;
use Yii;
use app\models\GroupVisibility;
use app\models\GroupVisibilitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GroupVisibilityController implements the CRUD actions for GroupVisibility model.
 */
class GroupVisibilityController extends Controller
{
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
     * Lists all GroupVisibility models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GroupVisibilitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GroupVisibility model.
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
     * Creates a new GroupVisibility model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GroupVisibility();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GroupVisibility model.
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
     * Deletes an existing GroupVisibility model.
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
     * Finds the GroupVisibility model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GroupVisibility the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GroupVisibility::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpdate_position()
    {
        $request = Yii::$app->request->get();
        global $group_id;
        $group_id = $request['group_id'];
        $group_key = GroupKey::find()->where(['group_id' => $group_id])->all();
        // defining all existing dates
//        $keys = KeyPosition::find()->orderBy('date desc')->all();
//        $i=0;
//        for($i=0;$i<count($keys);$i++){
//            $existing_dates[$i] = date('d.m.Y', $keys[$i]['date']);
//        }
//        $existing_dates = array_values(array_unique($existing_dates));
//        var_dump($existing_dates[2]);

//        $i=0;
//        $items=[];
//        foreach($group_key as $gk) {
//
//            /** @var GroupKey $gk */
////            $res = KeyPosition::find()
////                ->where(['key_id' => $gk->key_id])
////                ->andWhere(['key_position.time_from_today' => KeyPosition::find()->select('max(time_from_today)')->from(KeyPosition::tableName(). ' kp2')->where('kp2.id = '.$gk->key_id)->andWhere('kp2.date = key_position.date')->orderBy('time_from_today DESC')])
////                ->groupBy('date')
////                ->orderBy('max(time_from_today)')
////                ->all();
////                ->groupBy('key_position.date')->createCommand()->rawSql;
//            $res = Yii::$app->getDb()->createCommand(
//                'select kp1.* from `key_position` kp1 inner join (
//                    SELECT kp2.key_id, kp2.date, max(kp2.time_from_today) as MaxTimeFromToday FROM `key_position` `kp2`
//                    where (kp2.key_id = :key_id)
//                    /*SELECT kp2.date, kp2.time_from_today as MaxTimeFromToday FROM `key_position` `kp2`*/
//                    group by kp2.date) as kp2
//                    /* ON  (kp2.date = kp1.date) AND (kp2.MaxTimeFromToday = kp1.time_from_today) */
//                  ON  (kp2.date = kp1.date) AND (kp2.MaxTimeFromToday = kp1.time_from_today) AND (kp2.key_id = kp1.key_id)
//                  WHERE kp1.key_id = :key_id
//                ', [
//                    ':key_id' => $gk->key_id,
//                ]
//            )->queryAll();
////            )->rawSql;
//
//
////            foreach($res as $result){
////                var_dump($res[0]['date']);
////                var_dump($res[0]['position']);
////            }
////            die;
//
//            array_push($items, $res);
//            $i++;
//
//        }
////        echo '<pre>';
////        print_r($items);
////        die;
//        //total quantity of the query elements
//        $total_items = $i;
////        var_dump($total_items);die;
//
//
//
//
//
//
////        var_dump($items[0]);die;
//        $ten=0;
//        $i=0;
////        for($i=0; $i<count($items); $i++){
////            echo '<pre>';
////            var_dump($items[$i][1]);
////
////        }
//
////        $result = array_search('1457827200', $items[0][0]);
//
//
//        $result=[];
//        $i=0;
//        for($i=0; $i<count($items); $i++){
//            for($n=0; $n<count($items[$i]); $n++) {
//                array_push($result, $items[$i][$n]);
//            }
//        }
//        //defining unique dates
//        $dates_array=[];
//        foreach($result as $r_d){
//            $dates_array[]=$r_d['date'];
//        }
//        $dates_array=array_unique($dates_array);
//        //loop through dates
//        foreach($dates_array as $u_date){
//            $total_items = 0;
//            $top_ten=0;
//            for($i=0; $i<count($result); $i++){
//
//                if(array_search($u_date, $result[$i])) {
//                    $total_items++;
//                    if($result[$i]['position'] <=10)
//                        $top_ten++;
//                }
//
//
//            }
//
//            //saving results here
////            echo $u_date.': ';
//////            echo $top_ten.'<br>';
//            $top_ten = $top_ten / $total_items * 100;
//            $this->savePosition($group_id, $u_date, $top_ten);
//            //die;
//        }
//
//
//        //loop through results
////        for($i=0; $i<count($result); $i++){
////            if(array_search('1457827200', $result[$i])) {
////                echo '<pre>' . $i;
////                echo 'pos:'.$result[$i]['position'];
////                if($result[$i]['position'] <=10)
////                    $top_ten++;
////            }
////        }
//
//
//







//        var_dump(array_search('1457827200', $result[3]));











//
////        for($i=0; $i<count($existing_dates); $i++){
//            $res = KeyPosition::find()->where(['key_id' => $group_key[$i]['key_id']])->orderBy('date desc')->all();
////            var_dump($res);die;
//
//            for($n=0; $n<count($res); $n++){
////                if($existing_dates[1] == date('d.m.Y', $res[$n]['date'])) {
//                    var_dump($res[$n]['key_id']);
//                    var_dump(date('d.m.Y', $res[$n]['date']));
////                }
//            }
//
//
//
//
////        }



        // getting all the needed keys of the group
        $i=0;
        for($i=0; $i < count($group_key); $i++){
            $key[$i] = KeyPosition::find()->where(['key_id' => $group_key[$i]['key_id']])->orderBy('date desc')->one();
        }
        // counting elements for top
        $i=0;
        $included = 0;
        for($i=0; $i < count($key); $i++){
            if($key[$i]['position'] <= 10)
                $included++;
        }
        // percentage counted
        $top = $included / count($key) * 100;

        $date = date('dmY');
        $id = md5($group_id . $date);
        $exists = GroupVisibility::find()->where(['id' => $id])->exists();

        if($exists) {
            $model = $this->findModel($id);
            $model->visibility = $top;
            $model->update($model->id);
        } else {
            $model = new GroupVisibility();
            $model->group_id = $group_id;
            $model->date = $date;
            $model->id = $id;
            $model->visibility = $top;
            $model->save();
        }


        return $this->redirect(Yii::$app->request->referrer);

    }

    public function savePosition($group_id, $date, $top){
        $id = md5($group_id . $date);
        $exists = GroupVisibility::find()->where(['id' => $id])->exists();

        if($exists) {
            $model = $this->findModel($id);
            $model->visibility = $top;
            $model->update($model->id);
        } else {
            $model = new GroupVisibility();
            $model->group_id = $group_id;
            $model->date = $date;
            $model->id = $id;
            $model->visibility = $top;
            $model->save();
        }

    }
}

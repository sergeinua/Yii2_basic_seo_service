<?php

namespace app\controllers;

use Yii;
use app\models\ProdvigatorOrganic;
use app\models\ProdvigatorOrganicSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Projects;

/**
 * ProdvigatorOrganicController implements the CRUD actions for ProdvigatorOrganic model.
 */
class ProdvigatorOrganicController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ProdvigatorOrganic models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProdvigatorOrganicSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProdvigatorOrganic model.
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
     * Creates a new ProdvigatorOrganic model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProdvigatorOrganic();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ProdvigatorOrganic model.
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
     * Deletes an existing ProdvigatorOrganic model.
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
     * Finds the ProdvigatorOrganic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProdvigatorOrganic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProdvigatorOrganic::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetData(){
        $token = Yii::$app->params['prodvigator_token'];
        $project_id = Yii::$app->request->get('project_id');
        $domain = Projects::find()->where(['id' => $project_id])->one()->title;
        $url = 'http://api.prodvigator.ru/v3/domain_keywords?query=' . $domain . '&token=' . $token;
        $result = json_decode(file_get_contents($url));
        ProdvigatorOrganic::deleteAll();
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
            $model->region_queries_count_wide = $item->region_queries_count_wide;
//            $model->types = $item->types;
//            $model->geo_names = $item->geo_names;
            $model->save();
        endforeach;
    }
}

<?php

namespace yuncms\transaction\backend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\bootstrap\ActiveForm;
use yii\web\NotFoundHttpException;
use yuncms\transaction\models\TransactionChannel;
use yuncms\transaction\backend\models\TransactionChannelSearch;
use yuncms\web\Controller;
use yuncms\web\Response;

/**
 * ChannelController implements the CRUD actions for TransactionChannel model.
 */
class ChannelController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'batch-delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TransactionChannel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransactionChannelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TransactionChannel model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TransactionChannel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TransactionChannel();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('yuncms', 'Create success.'));
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TransactionChannel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('yuncms', 'Update success.'));
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Configuration an existing TransactionChannel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionConfiguration($id)
    {
        $channel = $this->findModel($id);
        $model = $channel->getSettingsModel();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post(), '')) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('yuncms/transaction', 'Configuration success.'));
            return $this->redirect(['view', 'id' => $id]);
        } else {
            return $this->render('configuration', [
                'model' => $model,
                'channel' => $channel
            ]);
        }
    }

    /**
     * Deletes an existing TransactionChannel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', Yii::t('yuncms', 'Delete success.'));
        return $this->redirect(['index']);
    }

    /**
     * Batch Delete existing TransactionChannel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionBatchDelete()
    {
        if (($ids = Yii::$app->request->post('ids', null)) != null) {
            foreach ($ids as $id) {
                $model = $this->findModel($id);
                $model->delete();
            }
            Yii::$app->getSession()->setFlash('success', Yii::t('yuncms', 'Delete success.'));
        } else {
            Yii::$app->getSession()->setFlash('success', Yii::t('yuncms', 'Delete failed.'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the TransactionChannel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TransactionChannel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TransactionChannel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException (Yii::t('yii', 'The requested page does not exist.'));
        }
    }
}

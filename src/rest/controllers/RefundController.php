<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\transaction\rest\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yuncms\rest\Controller;
use yuncms\transaction\rest\models\TransactionRefund;

/**
 * Class RefundController
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class RefundController extends Controller
{
    /**
     * 创建退款单
     * @return TransactionRefund
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $model = new TransactionRefund();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (($model->save()) != false) {
            Yii::$app->getResponse()->setStatusCode(201);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

    /**
     * 查看退款情况
     * @param string $id
     * @return TransactionRefund
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->findModel($id);
    }

    /**
     * 获取支付单号
     * @param string $id
     * @return TransactionRefund
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        if (($model = TransactionRefund::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException("Charge not found: $id");
        }
    }
}
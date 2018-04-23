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
use yuncms\transaction\models\TransactionCharge;

/**
 * Class ChargeController
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class ChargeController extends Controller
{

    /**
     * 创建支付单
     * @return TransactionCharge
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $model = new TransactionCharge();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (($model->save()) != false) {
            Yii::$app->getResponse()->setStatusCode(201);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

    /**
     * 关闭支付
     * @param integer $id
     * @return TransactionCharge
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\UnknownClassException
     */
    public function actionClose($id)
    {
        $model = $this->findModel($id);
        return $model->setClose();
    }

    /**
     * 查询渠道状态
     * @param integer $id
     * @return TransactionCharge
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\UnknownClassException
     */
    public function actionQuery($id)
    {
        $model = $this->findModel($id);
        return $model->queryChannel();
    }

    /**
     * 查看支付情况
     * @param string $id
     * @return TransactionCharge
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->findModel($id);
    }

    /**
     * 获取支付单号
     * @param string $id
     * @return TransactionCharge
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        if (($model = TransactionCharge::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException("Charge not found: $id");
        }
    }
}
<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\balance\rest\controllers;

use Yii;
use yii\web\ServerErrorHttpException;
use yuncms\balance\models\BalanceRecharge;
use yuncms\rest\Controller;

/**
 * Class RechargeController
 * @package yuncms\balance\rest\controllers
 */
class RechargeController extends Controller
{

    /**
     * 余额充值
     * @return BalanceRecharge
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $model = new BalanceRecharge();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (($model->save()) != false) {
            Yii::$app->getResponse()->setStatusCode(201);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }
}

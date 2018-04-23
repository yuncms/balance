<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\balance\rest\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\ServerErrorHttpException;
use yuncms\rest\Controller;
use yuncms\balance\rest\models\BalanceBonus;
use yuncms\balance\rest\models\UserSettleAccount;


/**
 * 余额操作控制器
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class BalanceController extends Controller
{

    /**
     * 提现渠道
     * @throws \yii\base\InvalidConfigException
     * @throws ServerErrorHttpException
     */
    public function actionSettleAccount()
    {
        $model = new UserSettleAccount();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (($model->save()) != false) {
            Yii::$app->getResponse()->setStatusCode(201);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

    /**
     * 余额增送
     * @return BalanceBonus
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionBonus()
    {
        $model = new BalanceBonus();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (($model->save()) != false) {
            Yii::$app->getResponse()->setStatusCode(201);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

    /**
     * 余额转账
     * @throws \yii\base\InvalidConfigException
     * @throws ServerErrorHttpException
     */
    public function actionTransfer()
    {
        $model = new TransactionBalanceTransfer();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (($model->save()) != false) {
            Yii::$app->getResponse()->setStatusCode(201);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }
}
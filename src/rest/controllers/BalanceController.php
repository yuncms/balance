<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\balance\rest\controllers;

use Yii;
use yii\web\ServerErrorHttpException;
use yuncms\balance\rest\models\BalanceRecharge;
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
     * 余额充值
     * @return BalanceRecharge
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRecharge()
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
}

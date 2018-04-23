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
use yuncms\balance\rest\models\BalanceTransaction;
use yuncms\balance\rest\models\BalanceTransfer;
use yuncms\balance\rest\models\SettleAccount;
use yuncms\balance\rest\models\Withdrawal;

/**
 * 余额操作控制器
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class BalanceController extends Controller
{

    /**
     * 获取钱包明细
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function actionTransaction()
    {
        $query = BalanceTransaction::find()->with('user');
        if (!empty($filter)) {
            $query->andWhere($filter);
        }
        return Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'id' => SORT_ASC,
                ]
            ],
        ]);
    }

    /**
     * 提现渠道
     * @throws \yii\base\InvalidConfigException
     * @throws ServerErrorHttpException
     */
    public function actionSettleAccount()
    {
        $model = new TransactionSettleAccount();
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
     * @return TransactionBalanceBonus
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionBonus()
    {
        $model = new TransactionBalanceBonus();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if (($model->save()) != false) {
            Yii::$app->getResponse()->setStatusCode(201);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

    /**
     * 余额提现
     * @return TransactionWithdrawal
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionWithdrawal()
    {
        $model = new TransactionWithdrawal();
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
<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\balance\rest\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\IndexAction;
use yuncms\balance\models\BalanceTransaction;
use yuncms\rest\ActiveController;
use yuncms\balance\rest\models\BalanceTransfer;

/**
 * 余额转账记录
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class TransferController extends ActiveController
{
    public $modelClass = BalanceTransfer::class;

    /**
     * 不允许新建修改和删除
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['update'], $actions['delete']);
        return $actions;
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     *
     * @param IndexAction $action
     * @param mixed $filter
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function prepareDataProvider(IndexAction $action, $filter)
    {
        $query = BalanceTransfer::find()->where(['user_id' => Yii::$app->user->getId()]);
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
     * 发起转账
     * @return bool|\yuncms\db\ActiveRecord
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionCreate()
    {
        Yii::$app->balanceManager->increase(10000000, 1, BalanceTransaction::TYPE_RECHARGE,'充值');
        return Yii::$app->balanceManager->transfer(10000000, 1, 10000);
    }
}
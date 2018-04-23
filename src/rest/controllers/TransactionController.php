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
use yuncms\rest\ActiveController;
use yuncms\balance\rest\models\BalanceTransaction;

/**
 * 余额交易记录
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class TransactionController extends ActiveController
{
    public $modelClass = BalanceTransaction::class;

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
        $query = BalanceTransaction::find()->where(['user_id' => Yii::$app->user->getId()]);
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


}
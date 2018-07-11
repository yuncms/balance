<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\balance\rest\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yuncms\rest\Controller;
use yuncms\balance\rest\models\BalanceTransaction;

/**
 * 余额交易记录
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class TransactionController extends Controller
{
    /**
     * @param string $month
     * @return object|ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex($month = null)
    {
        $query = BalanceTransaction::find()->where(['user_id' => Yii::$app->user->id]);
        if ($month != null) {
            $query->andWhere(["FROM_UNIXTIME(created_at,'%Y-%m')" => $month]);
        } else {
            $query->andWhere(["FROM_UNIXTIME(created_at,'%Y-%m')" => date('Y-m')]);
        }

        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }
        return Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $query,
            'pagination' => [
                'params' => $requestParams
            ],
            'sort' => [
                'params' => $requestParams,
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'id' => SORT_DESC,
                ]
            ],
        ]);
    }


}

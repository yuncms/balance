<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\transaction\frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yuncms\transaction\models\TransactionCharge;
use yuncms\web\Controller;

/**
 * Class ChargeController
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class ChargeController extends Controller
{
    /**
     * @param $id
     */
    public function actionPay($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->isAjax) {

        }
        return $this->redirect(['/payment/default/index', 'id' => $payment->id]);
        return $this->render('pay', ['payment' => $payment, 'paymentParams' => $paymentParams]);
    }

    /**
     * 获取模型
     * @param int $id
     * @return TransactionCharge
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        if (($model = TransactionCharge::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested charge does not exist.');
        }
    }
}
<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\transaction\frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yuncms\transaction\models\TransactionChannel;
use yuncms\transaction\models\TransactionCharge;
use yuncms\web\Controller;
use yuncms\web\Response;

/**
 * Class ResponseController
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class ResponseController extends Controller
{
    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = false;

    /**
     * 支付后跳转
     * @param string $channel 渠道标识
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\UnknownClassException
     */
    public function actionCallback($channel)
    {
        $model = $this->findModel($id);
        $channel = $model->getChannelObject();
        $channel->callback(Yii::$app->request, $this->paymentId, $this->money, $this->message, $this->payId);
        return $this->redirect(['/payment/default/return', 'id' => $this->paymentId]);
    }

    /**
     * 服务器端通知
     * @param string $channel 渠道标识
     * @return Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\UnknownClassException
     */
    public function actionNotice($channel)
    {
        $channel = TransactionChannel::getChannelByIdentity($channel);
        $channel->notice(Yii::$app->request, Yii::$app->response);
        return Yii::$app->response;
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
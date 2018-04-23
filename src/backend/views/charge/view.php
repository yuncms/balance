<?php

use yuncms\helpers\Html;
use yuncms\widgets\DetailView;
use yuncms\admin\widgets\Box;
use yuncms\admin\widgets\Toolbar;
use yuncms\admin\widgets\Alert;

/* @var $this yii\web\View */
/* @var $model yuncms\transaction\models\TransactionCharge */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yuncms/transaction', 'Manage Transaction Charge'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 transaction-charge-view">
            <?= Alert::widget() ?>
            <?php Box::begin([
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget(['items' => [
                        [
                            'label' => Yii::t('yuncms/transaction', 'Manage Transaction Charge'),
                            'url' => ['index'],
                        ],
                        [
                            'label' => Yii::t('yuncms/transaction', 'Create Transaction Charge'),
                            'url' => ['create'],
                        ],
                        [
                            'label' => Yii::t('yuncms/transaction', 'Update Transaction Charge'),
                            'url' => ['update', 'id' => $model->id],
                            'options' => ['class' => 'btn btn-primary btn-sm']
                        ],
                        [
                            'label' => Yii::t('yuncms/transaction', 'Delete Transaction Charge'),
                            'url' => ['delete', 'id' => $model->id],
                            'options' => [
                                'class' => 'btn btn-danger btn-sm',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                    'method' => 'post',
                                ],
                            ]
                        ],
                    ]]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'paid:boolean',
                    'refunded:boolean',
                    'reversed:boolean',
                    'channel',
                    'order_no',
                    'amount',
                    'currency',
                    'subject',
                    'body',
                    'client_ip',
                    'time_paid:datetime',
                    'time_expire:datetime',
                    'transaction_no',
                    'amount_refunded',
                    'failure_code',
                    'failure_msg',
                    'metadata:ntext',
                    'description',
                    'created_at:datetime',
                ],
            ]) ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>


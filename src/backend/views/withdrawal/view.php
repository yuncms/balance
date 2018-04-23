<?php

use yuncms\helpers\Html;
use yuncms\widgets\DetailView;
use yuncms\admin\widgets\Box;
use yuncms\admin\widgets\Toolbar;
use yuncms\admin\widgets\Alert;

/* @var $this yii\web\View */
/* @var $model yuncms\transaction\models\TransactionWithdrawal */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yuncms/transaction', 'Manage Transaction Withdrawal'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 transaction-withdrawal-view">
            <?= Alert::widget() ?>
            <?php Box::begin([
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget(['items' => [
                        [
                            'label' => Yii::t('yuncms/transaction', 'Manage Transaction Withdrawal'),
                            'url' => ['index'],
                        ],
                        [
                            'label' => Yii::t('yuncms/transaction', 'Create Transaction Withdrawal'),
                            'url' => ['create'],
                        ],
                        [
                            'label' => Yii::t('yuncms/transaction', 'Update Transaction Withdrawal'),
                            'url' => ['update', 'id' => $model->id],
                            'options' => ['class' => 'btn btn-primary btn-sm']
                        ],
                        [
                            'label' => Yii::t('yuncms/transaction', 'Delete Transaction Withdrawal'),
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
                    'user_id',
                    'status',
                    'amount',
                    'channel',
                    'metadata:ntext',
                    'extra:ntext',
                    'created_at',
                    'canceled_at',
                    'succeeded_at',
                ],
            ]) ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>


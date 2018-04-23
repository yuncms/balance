<?php

use yuncms\helpers\Html;
use yuncms\helpers\Json;
use yuncms\widgets\DetailView;
use yuncms\admin\widgets\Box;
use yuncms\admin\widgets\Toolbar;
use yuncms\admin\widgets\Alert;

/* @var $this yii\web\View */
/* @var $model yuncms\transaction\models\TransactionChannel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yuncms/transaction', 'Manage Transaction Channel'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 transaction-channel-view">
            <?= Alert::widget() ?>
            <?php Box::begin([
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget(['items' => [
                        [
                            'label' => Yii::t('yuncms/transaction', 'Manage Transaction Channel'),
                            'url' => ['index'],
                        ],
                        [
                            'label' => Yii::t('yuncms/transaction', 'Create Transaction Channel'),
                            'url' => ['create'],
                        ],
                        [
                            'label' => Yii::t('yuncms/transaction', 'Update Transaction Channel'),
                            'url' => ['update', 'id' => $model->id],
                            'options' => ['class' => 'btn btn-primary btn-sm']
                        ],
                        [
                            'label' => Yii::t('yuncms/transaction', 'Configuration Transaction Channel'),
                            'url' => ['configuration', 'id' => $model->id],
                            'options' => ['class' => 'btn btn-primary btn-sm']
                        ],
                        [
                            'label' => Yii::t('yuncms/transaction', 'Delete Transaction Channel'),
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
                    'identity',
                    'name',
                    'className',
                    'title',
                    'description',
                    [
                        'attribute' => 'configuration',
                        'value' => function ($model) {
                            return Json::encode($model->configuration);
                        },
                        'label' => Yii::t('yuncms/transaction', 'Channel Configuration'),
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return $model->status == \yuncms\transaction\models\TransactionChannel::STATUS_ACTIVE ? Yii::t('yuncms', 'Active') : Yii::t('yuncms', 'Disable');
                        },
                        'label' => Yii::t('yuncms', 'Status'),
                    ],
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>


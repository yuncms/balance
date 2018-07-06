<?php

use yuncms\helpers\Html;
use yuncms\widgets\DetailView;
use yuncms\admin\widgets\Box;
use yuncms\admin\widgets\Toolbar;
use yuncms\admin\widgets\Alert;

/* @var $this yii\web\View */
/* @var $model yuncms\balance\models\BalanceRecharge */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yuncms\balance', 'Manage Balance Recharge'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 balance-recharge-view">
            <?= Alert::widget() ?>
            <?php Box::begin([
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget(['items' => [
                        [
                            'label' => Yii::t('yuncms\balance', 'Manage Balance Recharge'),
                            'url' => ['index'],
                        ],
                        [
                            'label' => Yii::t('yuncms\balance', 'Create Balance Recharge'),
                            'url' => ['create'],
                        ],
                        [
                            'label' => Yii::t('yuncms\balance', 'Update Balance Recharge'),
                            'url' => ['update', 'id' => $model->id],
                            'options' => ['class' => 'btn btn-primary btn-sm']
                        ],
                        [
                            'label' => Yii::t('yuncms\balance', 'Delete Balance Recharge'),
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
                    'succeeded',
                    'refunded',
                    'amount',
                    'channel',
                    'user_fee',
                    'charge_id',
                    'balance_bonus_id',
                    'balance_transaction_id',
                    'description',
                    'metadata:ntext',
                    'created_at',
                    'succeeded_at',
                ],
            ]) ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>


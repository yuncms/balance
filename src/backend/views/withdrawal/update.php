<?php

use yuncms\helpers\Html;
use yuncms\admin\widgets\Box;
use yuncms\admin\widgets\Toolbar;
use yuncms\admin\widgets\Alert;

/* @var $this yii\web\View */
/* @var $model yuncms\balance\models\BalanceWithdrawal */

$this->title = Yii::t('yuncms/balance', 'Update Transaction Withdrawal') . ': ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yuncms/balance', 'Manage Transaction Withdrawal'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('yuncms', 'Update');
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 transaction-withdrawal-update">
            <?= Alert::widget() ?>
            <?php Box::begin([
            'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget(['items' => [
                        [
                            'label' => Yii::t('yuncms/balance', 'Manage Transaction Withdrawal'),
                            'url' => ['index'],
                        ],
                        [
                            'label' => Yii::t('yuncms/balance', 'Create Transaction Withdrawal'),
                            'url' => ['create'],
                        ],
                    ]]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>

            <?= $this->render('_form', [
            'model' => $model,
            ]) ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>
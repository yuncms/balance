<?php

use yuncms\helpers\Html;
use yuncms\admin\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yuncms\balance\backend\models\BalanceRechargeSearch */
/* @var $form ActiveForm */
?>

<div class="balance-recharge-search pull-right">

    <?php $form = ActiveForm::begin([
        'layout' => 'inline',
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'succeeded') ?>

    <?php // echo $form->field($model, 'refunded') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'channel') ?>

    <?php // echo $form->field($model, 'user_fee') ?>

    <?php // echo $form->field($model, 'charge_id') ?>

    <?php // echo $form->field($model, 'balance_bonus_id') ?>

    <?php // echo $form->field($model, 'balance_transaction_id') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'metadata') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'succeeded_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('yuncms', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('yuncms', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

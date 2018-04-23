<?php

use yuncms\helpers\Html;
use yuncms\admin\widgets\ActiveForm;
use yuncms\transaction\models\TransactionChannel;

/* @var $this yii\web\View */
/* @var $model yuncms\transaction\backend\models\TransactionChannelSearch */
/* @var $form ActiveForm */
?>

<div class="transaction-channel-search pull-right">

    <?php $form = ActiveForm::begin([
        'layout' => 'inline',
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?// echo $form->field($model, 'id') ?>

    <?= $form->field($model, 'identity') ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'className') ?>

    <?= $form->field($model, 'status')->dropDownList([
        TransactionChannel::STATUS_ACTIVE => Yii::t('yuncms', 'Active'),
        TransactionChannel::STATUS_DISABLED => Yii::t('yuncms', 'Disable')
    ], [
        'prompt' => Yii::t('yuncms', 'Status')
    ]) ?>

    <?php // echo $form->field($model, 'extra') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('yuncms', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('yuncms', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

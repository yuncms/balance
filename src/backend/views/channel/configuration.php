<?php

use yuncms\helpers\Html;
use yuncms\admin\widgets\Box;
use yuncms\admin\widgets\Toolbar;
use yuncms\admin\widgets\Alert;
use yuncms\admin\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yuncms\transaction\models\SettingsModel */
/* @var $channel yuncms\transaction\models\TransactionChannel */
$this->title = Yii::t('yuncms/transaction', 'Configuration Transaction Channel');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yuncms/transaction', 'Manage Transaction Channel'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 transaction-channel-create">
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
                    ]]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>

            <?php $form = ActiveForm::begin(['layout' => 'horizontal', 'enableAjaxValidation' => true, 'enableClientValidation' => false,]); ?>
            <?= $form->field($model, 'identity')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'name')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'title')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'class')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'timeout')->textInput() ?>
            <?= $this->render('configuration/' . $channel->identity, [
                'form' => $form,
                'model' => $model,
                'channel' => $channel
            ]) ?>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <?= Html::submitButton(Yii::t('yuncms', 'Save'), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>
<?php

use yuncms\admin\widgets\ActiveForm;
use yuncms\transaction\channels\wechat\Wechat;

/* @var \yii\web\View $this */
/* @var ActiveForm $form */
?>

<?= $form->field($model, 'appId')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>

<?= $form->field($model, 'apiKey')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>

<?= $form->field($model, 'mchId')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>

<?= $form->field($model, 'signType')->dropDownList([
    'MD5' => Wechat::SIGNATURE_METHOD_MD5,
    'HMAC-SHA256' => Wechat::SIGNATURE_METHOD_SHA256
]) ?>
<div class="hr-line-dashed"></div>

<?= $form->field($model, 'privateKey')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>

<?= $form->field($model, 'publicKey')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>

<?php

use yuncms\admin\widgets\ActiveForm;
use yuncms\transaction\channels\alipay\Alipay;

/* @var \yii\web\View $this */
/* @var ActiveForm $form */
?>

<?= $form->field($model, 'appId')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>

<?= $form->field($model, 'pid')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>

<?= $form->field($model, 'alipayAccount')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>

<?= $form->field($model, 'signType')->dropDownList([
    'RSA' => Alipay::SIGNATURE_METHOD_RSA,
    'RSA2' => Alipay::SIGNATURE_METHOD_RSA2
]) ?>

<?= $form->field($model, 'privateKey')->textarea() ?>
<div class="hr-line-dashed"></div>

<?= $form->field($model, 'publicKey')->textarea() ?>
<div class="hr-line-dashed"></div>

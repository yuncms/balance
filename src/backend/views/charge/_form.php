<?php
use yuncms\helpers\Html;
use yuncms\admin\widgets\ActiveForm;

/* @var \yii\web\View $this */
/* @var yuncms\transaction\models\TransactionCharge $model */
/* @var ActiveForm $form */
?>
<?php $form = ActiveForm::begin(['layout'=>'horizontal', 'enableAjaxValidation' => true, 'enableClientValidation' => false,]); ?>

    <?= $form->field($model, 'paid')->inline(true)->boolean() ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'refunded')->inline(true)->boolean() ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'reversed')->inline(true)->boolean() ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'order_no')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'amount')->textInput() ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'currency')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'body')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'client_ip')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'time_paid')->textInput() ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'time_expire')->textInput() ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'transaction_no')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'amount_refunded')->textInput() ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'failure_code')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'failure_msg')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'metadata')->textarea(['rows' => 6]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>


<div class="form-group">
    <div class="col-sm-4 col-sm-offset-2">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('yuncms', 'Create') : Yii::t('yuncms', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>


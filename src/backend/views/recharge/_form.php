<?php
use yuncms\helpers\Html;
use yuncms\admin\widgets\ActiveForm;

/* @var \yii\web\View $this */
/* @var yuncms\balance\models\BalanceRecharge $model */
/* @var ActiveForm $form */
?>
<?php $form = ActiveForm::begin(['layout'=>'horizontal', 'enableAjaxValidation' => true, 'enableClientValidation' => false,]); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'succeeded')->textInput() ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'refunded')->textInput() ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'channel')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'user_fee')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'charge_id')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'balance_bonus_id')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'balance_transaction_id')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'metadata')->textarea(['rows' => 6]) ?>    <div class="hr-line-dashed"></div>


<div class="form-group">
    <div class="col-sm-4 col-sm-offset-2">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('yuncms', 'Create') : Yii::t('yuncms', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>


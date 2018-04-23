<?php
use yuncms\helpers\Html;
use yuncms\admin\widgets\ActiveForm;

/* @var \yii\web\View $this */
/* @var yuncms\transaction\models\TransactionWithdrawal $model */
/* @var ActiveForm $form */
?>
<?php $form = ActiveForm::begin(['layout'=>'horizontal', 'enableAjaxValidation' => true, 'enableClientValidation' => false,]); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'status')->textInput() ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'channel')->textInput(['maxlength' => true]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'metadata')->textarea(['rows' => 6]) ?>    <div class="hr-line-dashed"></div>

    <?= $form->field($model, 'extra')->textarea(['rows' => 6]) ?>    <div class="hr-line-dashed"></div>


<div class="form-group">
    <div class="col-sm-4 col-sm-offset-2">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('yuncms', 'Create') : Yii::t('yuncms', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>


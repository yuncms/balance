<?php
use yuncms\admin\widgets\ActiveForm;
use yuncms\transaction\models\TransactionChannel;

/* @var \yii\web\View $this */
/* @var ActiveForm $form */
/* @var  TransactionChannel $channel */
?>
<?= $this->render('alipay', [
    'form' => $form,
    'model' => $model,
    'channel' => $channel
]) ?>

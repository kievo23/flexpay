<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mpesa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mpesa-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'transaction_reference')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'transaction_timestamp')->textInput() ?>

    <?= $form->field($model, 'sender_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

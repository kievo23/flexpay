<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Auth */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'firstName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lastName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category')->dropDownList(['merchant'=>'Merchant','admin'=>'Admin'],['prompt'=>'--select category--']) ?>

    <?= $form->field($model, 'sms_amount')->textInput() ?>

    <?= $form->field($model, 'interest_rate')->textInput() ?>
    
    <?= $form->field($model, 'till_no')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
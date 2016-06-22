<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchCustomer */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Change Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-active">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);

    //echo "<pre>";
    //print_r(yii::$app->user->identity);
    //echo "</pre>";
    if(isset($feedback) && !empty($feedback) ){ 
    ?>
        <div class="alert alert-info">
          <strong>Info!</strong> <?php print_r($feedback); ?>
        </div>
    <?php
    }
    ?>

    <div class="change-password-form">

    <?php $form = ActiveForm::begin(['options'=>['class'=>'']]); ?>
        <div class="form-group">
        <?= $form->field($model, 'old_pass')->passwordInput(['class' => 'form-control']) ?>
        </div>
        <div class="form-group">
        <?= $form->field($model, 'new_pass')->passwordInput(['class' => 'form-control']) ?>
        </div>
        <div class="form-group">
        <?= $form->field($model, 'repeat_pass')->passwordInput(['class' => 'form-control']) ?>
        </div>
        <div class="form-group">
            <?= Html::submitButton('Change Password', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
<hr>    


</div>
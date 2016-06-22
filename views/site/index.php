<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Flexpay Admin';
?>
<div class="site-index">

    <div class="jumbotron">
	<img src="<?= yii::$app->request->BaseUrl ?>/images/Flexpay9-03.png" width="250px">
        <p><?php
            if(!\Yii::$app->user->isGuest){
                echo "Welcome: ".Yii::$app->user->identity->username;
            }
            ?>
            
	
        </p>
    </div>

    <div class="body-content">
	<div class="row">
	</div>
        <div class="row">
            <div class="col-lg-12 text-center">

            <?php

            if(\Yii::$app->user->isGuest){

             $form = ActiveForm::begin([
                'id' => 'login-form',
                'action' => ['site/login'],
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-1 control-label'],
                ],
            ]); ?>

            <div class="form-group">
                <div class="col-lg-offset-1 col-lg-11">
                    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-lg-offset-1 col-lg-11">
                    <?= $form->field($model, 'password')->passwordInput() ?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-lg-offset-1 col-lg-11">
                     <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>
                </div>
            </div>

            <div class="form-group">
                <div class=" col-lg-11">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            </div>

        <?php ActiveForm::end(); 

        }else{
            ?>

            <div class="row text-center">
                <div class="col-md-6 col-xs-12 homeishome">
                    <div class="col-md-4">
                        
                        <i class="fa fa-fw fa-bank"></i> <br> 
                        Active Bookings <br>                  
                        <?= $active->totalCount ?> Ksh
                    </div>
                    <div class="col-md-4">
                        <i class="fa fa-fw fa-pie-chart"></i> <br>                     
                        Overdue Bookings <br>                
                        <?= $overdue->totalCount ?> Ksh
                    </div>
                    <div class="col-md-4">                
                        <i class="fa fa-fw fa-navicon"></i><br> 
                        Successful Bookings <br>
                        <?= $Successful->totalCount ?> Ksh
                    </div>
                    <div class="col-md-12 totals">
                        <i class="fa fa-fw fa-folder"></i><br> 
                        Total Cash <br>
                        <?= $Successful->totalCount+$overdue->totalCount+$active->totalCount ?> Ksh
                    </div>
                </div>
                <div class="col-md-6 col-xs-12 homeishome">
                    <div class="col-md-4">                        
                        <i class="fa fa-fw fa-bank"></i> <br> 
                        Value of the Products under Active Booking <br>                  
                        
                            <?php
                            print_r($activedata);
                        ?>
                    </div>
                </div>
            </div>

        <?php
        }
    ?>
                
            </div>            
        </div>

    </div>
</div>

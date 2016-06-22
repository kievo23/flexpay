<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Customer */

$this->title = 'Send SMS';
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


echo "Message Credit Available: ".yii::$app->user->identity->sms_amount;

    //echo "<pre>";
    //print_r($feedback);
    //echo "</pre>";
	if(isset($feedback) && !empty($feedback) ){	
?>
	<div class="alert alert-info">
	  <strong>Info!</strong> <?php print_r($feedback); ?>
	</div>
<?php
	}
?>

<div class="customer-sendSMS">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_sms', [
        'model' => $model,
    ]) ?>

</div>

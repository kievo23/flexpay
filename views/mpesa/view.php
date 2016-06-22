<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mpesa */

$this->title = $model->transaction_reference;
$this->params['breadcrumbs'][] = ['label' => 'Mpesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mpesa-view">

    <h1><?= Html::encode($this->title) ?></h1>    

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'transaction_reference',
            'transaction_timestamp',
            'sender_phone',
            'first_name',
            'last_name',
            'amount',
            'status',
        ],
    ]) ?>

</div>

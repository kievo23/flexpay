<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchCustomer */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Overdue Bookings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 

    ?>

    <?php $form = ActiveForm::begin(['options'=>['class'=>'form-inline']]); ?>
        <div class="form-group">
        <?= $form->field($model, 'phone')->textInput() ?>
        </div>
        <div class="form-group">
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?><hr>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn'
            ],
            'id',
            'phone',
            'code',
            'name',
            'source',            
            [
                'attribute' => 'price',
                'label' => 'Merchant Price'
            ],
            'flexpay_price',
            [
                'attribute' => 'sum(m.amount)',
                'label' => 'Amount Paid'
            ],
            'balance',
            'DueDate',
        ],
    ]); ?>

</div>
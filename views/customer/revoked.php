<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchCustomer */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Revoked Bookings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-revoked">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);

    //echo "<pre>";
    //print_r(yii::$app->user->identity);
    //echo "</pre>";

    ?>  
    

    <div class="customer-form">

    <?php $form = ActiveForm::begin(['options'=>['class'=>'form-inline']]); ?>
        <div class="form-group">
        <?= $form->field($model, 'phone')->textInput() ?>
        </div>
        <div class="form-group">
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
<hr>    
<button class="btn btn-primary" id="exportxls">Export to Excel</button>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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
            'dated',
            'DueDate',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'buttons' => [                    
                    'update' => function ($url,$model,$key) {                        
                        return Html::a('Edit',['update','id'=>$model['id']]);  
                        //return print_r($model['id']);  
                    },
                ],
            ],
        ],
    ]); ?>

</div>
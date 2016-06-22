<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchReceipts */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Receipts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receipts-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn'
            ],            
            'receipt_no',
            'productcode',
            'product.name',
            'product.source',
            'phone',
            [
            	'attribute' => 'status',
            	'value' => function($model) {
		            if ($model->status == 1){
		            	return "Not Collected";
		            }elseif($model->status == 7){
		            	return "Collected";
		            }
	            }
            ],
            'extra',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}{update}'
            ],
        ],
    ]); ?>

</div>
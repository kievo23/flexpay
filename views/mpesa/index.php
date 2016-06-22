<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchMpesa */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mpesa Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mpesa-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <button class="btn btn-primary" id="exportxls">Export to Excel</button>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'transaction_reference',
            'transaction_timestamp',
            'sender_phone',
            'first_name',
            'last_name',
             'amount',
            // 'status',
		'product_code',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}'
            ],
        ],
    ]); ?>

</div>
<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchCustomer */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);

    

     ?>

    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'phone',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    if ($model->status == 1){
                        return "Revoked";
                    }elseif($model->status == 0){
                        return "Active";
                    }
                }
            ],
            'code',
            'date',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}'
            ],
        ],
    ]); ?>

</div>

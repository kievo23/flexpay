<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoicesSeacrh */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoices-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="row">
        <div class="col-md-6 col-xs-12">
        <h3>With 212352 Till Number</h3>
             <?= GridView::widget([
                'dataProvider' => $small_merchants,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    'firstName',
                    'lastName',
                    'username',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{link}',
                        'buttons' => [
                            'link' => function ($url,$model,$key) {
                                return Html::a('view', ['/invoices/invoices','id'=> $model->id]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
        <div class="col-md-6 col-xs-12">
        <h3>With Custom Till Number</h3>
             <?= GridView::widget([
                'dataProvider' => $big_merchants,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    'firstName',
                    'lastName',
                    'username',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{link}',
                        'buttons' => [
                                'link' => function ($url,$model,$key) {
                                    return Html::a('view', ['/invoices/invoices','id'=> $model->id]);
                                },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>

</div>

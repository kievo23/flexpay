<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\Invoices;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoicesSeacrh */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoicing History';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoices-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <div class="row">
             <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'total',
                    [
                        'attribute' => 'monthg',
                        'label' => 'Month',
                        'value' => function ($model) {
                                $mons = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");
                                return $mons[$model['monthg']];
                            },
                    ],
                    [
                        'attribute' => 'yearg',
                        'label' => 'Year',
                        'value' => function ($model) {
                            return $model['yearg'];
                        }
                    ],
                    [
                        'attribute' => 'invoice_no',
                        'label' => 'Invoice Number',
                        'value' => function ($model) {
                            return $model['invoice_no'];
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{link}',
                        'buttons' => [
                            'link' => function ($url,$model,$key) {
                                if(yii::$app->user->identity->till_no == "212352" && yii::$app->user->identity->category == 'admin'){
                                    $session = Yii::$app->session;
                                    $invoiceStatus = Invoices::find()
                                    ->where(['for_the_month'=>$model['yearg'].'-'.$model['monthg'].'-'.'00',
                                        'user_id'=>yii::$app->user->identity->id,
                                        'user_id_to'=>$session->get('user_idt')])
                                    ->one();

                                    $invoice = Invoices::findOne($model['invoice_no']);

                                    if(!empty($invoice)) {
                                        return "<span><i class='fa fa-fw fa-money'></i> invoiced </span>";
                                    }else{
                                        $mons = array(1 => "01", 2 => "02", 3 => "03", 4 => "04", 5 => "05",
                                         6 => "06", 7 => "07", 8 => "08", 9 => "09", 10 => "10", 11 => "11", 12 => "12");
                                        return Html::a('<i class="fa fa-fw fa-money"></i> invoice', 
                                        ['/invoices/createinvoice',
                                        'amount'=> $model['total'],
                                        'user_id'=> $session->get('user_idt'),
                                        'for_the_year'=> $model['yearg'],
                                        'for_the_month'=>$mons[$model['monthg']]]);
                                    }                            
                                }elseif(yii::$app->user->identity->till_no != "212352" && yii::$app->user->identity->category == 'merchant'){
                                    $session = Yii::$app->session;
                                    $invoice = Invoices::findOne($model['invoice_no']);
                                    if(empty($invoice)){                                        
                                        return "<span><i class='fa fa-fw fa-money'></i>Has Not Invoiced</span>";
                                    }else{
                                        return "<span><i class='fa fa-fw fa-money'></i>Invoiced</span>";
                                    }
                                }                                
                            },
                        ],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{paid}',
                        'buttons' => [                    
                            'paid' => function ($url,$model,$key) {
                                if(yii::$app->user->identity->till_no == "212352" && yii::$app->user->identity->category == 'admin'){
                                    $session = Yii::$app->session;
                                    $invoiceStatus = Invoices::find()
                                        ->where(['for_the_month'=>$model['yearg'].'-'.$model['monthg'].'-'.'00',
                                            'user_id'=>yii::$app->user->identity->id,
                                            'user_id_to'=>$session->get('user_idt')])
                                        ->one();
                                    if(!empty($invoiceStatus->for_the_month)){
                                        if($invoiceStatus->user_id == yii::$app->user->identity->id){
                                            if($invoiceStatus->collected == '0'){
                                                return Html::a('Not Paid',
                                                 ['/invoices/update','id'=>$invoiceStatus->id]);
                                            }else{
                                                return Html::a('<i class="fa fa-fw fa-thumbs-up"></i> Paid', 
                                                ['/invoices/update','id'=>$invoiceStatus->id]);
                                            } 
                                        }elseif($invoiceStatus->user_id_to == yii::$app->user->identity->id){
                                            if($invoiceStatus->collected == '0'){
                                                return "<span>Not Paid</span>";
                                            }else{
                                                return "<span><i class='fa fa-fw fa-thumbs-up'></i>Paid</span>";
                                            }
                                        }
                                    }
                                }elseif(yii::$app->user->identity->till_no != "212352" && yii::$app->user->identity->category == 'merchant'){
                                        $invoiceStatus = Invoices::find()
                                        ->where(['for_the_month'=>$model['yearg'].'-'.$model['monthg'].'-'.'00',
                                            'user_id_to'=>yii::$app->user->identity->id])
                                        ->one();
                                        if(empty($invoiceStatus->for_the_month)){
                                            return "Not Yet";
                                        }elseif($invoiceStatus->user_id == yii::$app->user->identity->id){
                                            if($invoiceStatus->collected == '0'){
                                                return Html::a('Not Paid',
                                                 ['/invoices/update','id'=>$invoiceStatus->id]);
                                            }else{
                                                return Html::a('<i class="fa fa-fw fa-thumbs-up"></i> Paid', 
                                                ['/invoices/update','id'=>$invoiceStatus->id]);
                                            } 
                                        }elseif($invoiceStatus->user_id_to == yii::$app->user->identity->id){
                                            if($invoiceStatus->collected == '0'){
                                                return "<span>Not Paid</span>";
                                            }else{
                                                return "<span><i class='fa fa-fw fa-thumbs-up'></i>Paid</span>";
                                            }
                                        }
                                        
                                    }                      
                            },
                        ],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{print}',
                        'buttons' => [                    
                            'print' => function ($url,$model,$key) {
                                $session = Yii::$app->session;
                                if(yii::$app->user->identity->category == 'admin'){
                                    $user_id = $session->get('user_idt');
                                }else{
                                    $user_id = yii::$app->user->identity->id;
                                }                                
                                $month = $model['monthg'];
                                if(strlen($model['monthg']) == 1){
                                    $month = "0".$model['monthg'];
                                }
                                return Html::a('Print',['/invoices/print','amount'=>$model['total'],
                                    'user_id'=>$user_id,
                                    'year'=>$model['yearg'],
                                    'month'=>$month,
                                    'invoice_no'=>$model['invoice_no']]);                                           
                            },
                        ],
                    ],
                ],
            ]); ?>
    </div>
</div>
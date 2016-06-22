<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\Auth;
use app\models\Invoices;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoicesSeacrh */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoice';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoices-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="row">
        <button class="btn btn-primary" onclick="functionPrint()">Print</button>  
        <button class="btn btn-primary" id="exportxls">Export to Excel</button>       
        <h3></h3>
        <div class="row">
            <div class="col-md-5 col-print-5">
                <img src="<?= yii::$app->request->BaseUrl ?>/images/Flexpay9-03.png" width="250px">
            </div>
            <div class="col-md-6 col-print-6">
                <?php
                    
                    $userRecord = Invoices::find()->where("(user_id_to=".$details->user_id.") OR (".
                        "user_id_to=".$details->user_id_to.")")->one();
                ?>
                <h4>
                <?php

                    $toUser =Auth::findOne($details->user_id_to);
                ?>
                <div class="row">
                    <div class="col-md-8">
                        <strong>TO:</strong>
                        <?= $toUser->firstName ?> 
                    </div>
                </div> 
                </h4>
                <h4>  
                    <?php
                        $fromUser =Auth::findOne($details->user_id);
                    ?>
                    <h4>
                    <div class="row">
                        <div class="col-md-8">
                        <strong>FROM:</strong>
                            <?= $fromUser->firstName ?> 
                        </div> 
                    </div>                        
                    </h4>
                    
                </h4>
                <h4>
                <div class="row">
                    <div class="col-md-12">
                        <strong>TOTAL AMOUNT:</strong>
                        <?= $details->amount ?> KSH
                    </div> 
                </div>
                </h4>
                <H4> 
                <div class="row">
                    <div class="col-md-12">
                        <strong>RATE AMOUNT:</strong>
                        <?= $toUser->interest_rate * $details->amount / 100 ?> KSH
                    </div> 
                </div>                                         
                </h4> 
                <h4>
                    <?php
                    $mons = array(01 => "Jan", 02 => "Feb", 03 => "Mar", 04 => "Apr", 05 => "May", 06 => "Jun", 07 => "Jul", 08 => "Aug", 09 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");
                     
                     ?>
                     <div class="row">
                         <div class="col-md-8">
                            <strong>FOR THE MONTH:</strong>
                            <?= $mons[04]." - ".$year ?>
                        </div>
                     </div>                     
                </h4>                          
            </div>            
        </div>
            List of transaction
             <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'transaction_reference',
                    'sender_phone',
                    'first_name',
                    'last_name',
                    'amount',
                    'transaction_timestamp'
                ],
            ]); ?>        
    </div>

</div>

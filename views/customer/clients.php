<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchCustomer */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clients Bookings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-active">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);

    //echo "<pre>";
    //print_r(yii::$app->user->identity);
    //echo "</pre>";

    ?>
<hr>

<table class="table">
        <thead>
            <td>Client</td>
            <td>Client Username</td>
            <td>Active</td>
            <td>Overdue</td>
            <td>Successful</td>
            <td>Total</td>
        </thead>
        <tbody>
<?php
foreach ($usernames as $key => $value) {
    $total = 0;
    ?>  
    <tr>      
        <td><?= $value->empno ?></td>
        <td><?= $value->username ?></td>
        <td><?php 
            $q = 'select c.*, p.*, sum(m.amount) as total ,p.flexpay_price - sum(m.amount) AS balance,
                 ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate
                 from customer c
                 join product p on c.code = p.code
                 join mpesa m on c.phone = m.sender_phone
                 where m.status = 0 and ADDDATE(c.date,INTERVAL p.installments DAY) >= CURDATE()
                 and c.code like "'.$value->username.'%"';
                 $result = Yii::$app->db->createCommand($q)->queryAll();
                 if($result[0]['total'] == ""){
                    echo "0";
                 }else{
                    echo $result[0]['total']." Ksh";
                }                 
                 $total += $result[0]['total'];
        ?></td>
        <td><?php 
            $q = 'select c.*, p.*, sum(m.amount) as total ,p.flexpay_price - sum(m.amount) AS balance,
                 ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate
                 from customer c
                 join product p on c.code = p.code
                 join mpesa m on c.phone = m.sender_phone
                 where m.status = 0 and ADDDATE(c.date,INTERVAL p.installments DAY) <= CURDATE()
                 and c.code like "'.$value->username.'%"';
                 $result = Yii::$app->db->createCommand($q)->queryAll();
                 if($result[0]['total'] == ""){
                    echo "0";
                 }else{
                    echo $result[0]['total']." Ksh";
                } 
                 $total += $result[0]['total'];
        ?></td>
        <td><?php 
        $q = 'SELECT sum(flexpay_price) as total from product p join receipts r on p.code=r.productcode where r.productcode like "'. $value->username .'%"';
        $result = Yii::$app->db->createCommand($q)->queryAll();
        if($result[0]['total'] == ""){
                    echo "0";
                 }else{
                    echo $result[0]['total']." Ksh";
                } 
        $total += $result[0]['total'];
        ?></td>
        <td><?php 
        echo $total." Ksh";
        ?></td>
    </tr>        
<?php

}

?>
</tbody>
</table>
</tbody>
</table>
</div>
<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\DashboardAsset;

DashboardAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?php
        if(\Yii::$app->user->isGuest){
?>
    <style type="text/css">
        @media (min-width: 768px){
            #wrapper {
             padding-left: 80px !important; 
             padding-right: 80px !important; 
            }
        }        
    </style>
<?php
}
?>

<div id="wrapper" class="wrap">

    <?php

        //echo "<pre>";
       // print_r(Yii::$app->user);
        //echo "</pre>";

        if(!\Yii::$app->user->isGuest){

    ?>

        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="
                <?= yii::$app->request->BaseUrl ?>/index.php?r=site/index">Flexpay Admin</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">                
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?= yii::$app->user->identity->username ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            
                            <?php
                            echo Html::a('<i class="fa fa-fw fa-power-off"></i> Logout',
                                ['/site/logout'],
                                [
                                    'data-method'=>'post'
                                ]);
                        ?>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li>
                        <a href="<?= yii::$app->request->BaseUrl ?>/index.php?r=product/index"><i class="fa fa-fw fa-dashboard"></i> Products</a>
                    </li>
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> Bookings <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo" class="collapse">
                            <li>
                                <a href="<?= yii::$app->request->BaseUrl ?>/index.php?r=customer/active">
                                Active Bookings
                                </a>
                            </li>
                            <li>
                                <a href="<?= yii::$app->request->BaseUrl ?>/index.php?r=customer/overdue">
                                Overdue Bookings
                                </a>
                            </li>
                            <li>
                                <a href="<?= yii::$app->request->BaseUrl ?>/index.php?r=receipts/index">
                                Successful Bookings
                                </a>
                            </li>
                            <li>
                                <a href="<?= yii::$app->request->BaseUrl ?>/index.php?r=customer/revoked">
                                Revoked Bookings
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php

                    if(yii::$app->user->identity->category == 'admin'){
                        ?>
                        
                        
                        <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#clients"><i class="fa fa-fw fa-arrows-v"></i> Clients <i class="fa fa-fw fa-"></i></a>
                        <ul id="clients" class="collapse">
               <li>
                            <a href="<?= yii::$app->request->BaseUrl ?>/index.php?r=auth/index"><i class="fa fa-fw fa-edit"></i> Clients Accounts</a>
                        </li>
	       <li>
	     <a href="<?= yii::$app->request->BaseUrl ?>/index.php?r=customer/clients"><i class="fa fa-fw fa-euro"></i> Clients Summary</a>
	       </li>
            </ul>
                    </li>  
                    <li>
	                   <a href="<?= yii::$app->request->BaseUrl ?>/index.php?r=customer/index"><i class="fa fa-fw fa-user"></i> Customers</a>
	               </li>                  
                    
                        
                    <?php
                        }
                    ?>
                    
                    <li>
                            <a href="<?= yii::$app->request->BaseUrl ?>/index.php?r=mpesa/index"><i class="fa fa-fw fa-bar-chart-o"></i> Mpesa</a>
                    </li>
                    <li>
                          <a href="<?= yii::$app->request->BaseUrl ?>/index.php?r=customer/sendsms"><i class="fa fa-fw fa-envelope"></i> Send SMS</a>
                    </li>
                    <li>
                          <a href="<?= yii::$app->request->BaseUrl ?>/index.php?r=invoices/index"><i class="fa fa-fw fa-calculator"></i> Invoicing</a>
                        </li>
                    <li>
                          <a href="<?= yii::$app->request->BaseUrl ?>/index.php?r=customer/changepassword"><i class="fa fa-fw fa-edit"></i> Change Password</a>
                    </li>
                    
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>
        <?php
            }
        ?>

    
    
        <div id="page-wrapper" class="container">
            <div class="container-fluid">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= $content ?>
            </div>
        </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Flexpay <?= date('Y') ?></p>

        <p class="pull-right">Kenc Inc </p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
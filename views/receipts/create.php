<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Receipts */

$this->title = 'Create Receipts';
$this->params['breadcrumbs'][] = ['label' => 'Receipts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receipts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

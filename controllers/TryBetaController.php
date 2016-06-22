<?php

namespace app\controllers;

use Yii;
use app\models\Customer;
use app\models\SearchCustomer;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class TryController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
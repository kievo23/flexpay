<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\data\SqlDataProvider;
use yii\db\Connection;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new LoginForm();

        if(!\Yii::$app->user->isGuest){
                //Active
                $db = Yii::$app->getDb();
                if(yii::$app->user->identity->category == 'admin'){
                    $q = 'select sum(m.amount) as total ,p.flexpay_price - sum(m.amount) AS balance,
                     SUM( p.flexpay_price ) AS amountPrice,
                     ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate
                     from customer c
                     join product p on c.code = p.code
                     join mpesa m on c.phone = m.sender_phone
                     where m.status = 0 and ADDDATE(c.date,INTERVAL p.installments DAY) >= CURDATE()
                    ';
                    $activedata = $db->createCommand($q)->queryAll();
                 }elseif(yii::$app->user->identity->category == 'merchant'){
                     $q = 'select sum(m.amount) as total ,p.flexpay_price - sum(m.amount) AS balance,
                     SUM( p.flexpay_price ) AS amountPrice,
                     ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate
                     from customer c
                     join product p on c.code = p.code
                     join mpesa m on c.phone = m.sender_phone
                     where m.status = 0 and ADDDATE(c.date,INTERVAL p.installments DAY) >= CURDATE()
                     and c.code like "'.yii::$app->user->identity->username.'%"
                    ';
                    $activedata = $db->createCommand($q)->queryAll();
                 }
                

                $sql = Yii::$app->db->createCommand($q);
                $count = $sql->queryScalar();

                $dataProviderActive = new SqlDataProvider([
                   'sql' => $q,
                   'pagination' => [
                      'pageSize' => 30,
                    ],
                   'totalCount' => $count,
                ]);


                //OVERDUE
                if(yii::$app->user->identity->category == 'admin'){
                    $q = 'select sum(m.amount) as total ,p.flexpay_price - sum(m.amount) AS balance,
                     ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate
                     from customer c
                     join product p on c.code = p.code
                     join mpesa m on c.phone = m.sender_phone
                     where m.status = 0 and ADDDATE(c.date,INTERVAL p.installments DAY) <= CURDATE()
                     ';
                 }elseif(yii::$app->user->identity->category == 'merchant'){
                     $q = 'select sum(m.amount) as total ,p.flexpay_price - sum(m.amount) AS balance,
                     ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate
                     from customer c
                     join product p on c.code = p.code
                     join mpesa m on c.phone = m.sender_phone
                     where m.status = 0 and ADDDATE(c.date,INTERVAL p.installments DAY) <= CURDATE()
                     and c.code like "'.yii::$app->user->identity->username.'%"
                     ';
                 }
                $sql = Yii::$app->db->createCommand($q);
                $count = $sql->queryScalar();

                $dataProviderOverdue = new SqlDataProvider([
                   'sql' => $q,
                   'pagination' => [
                      'pageSize' => 30,
                    ],
                   'totalCount' => $count,
                ]);

                //Successful bookings
                
                if(yii::$app->user->identity->category == 'admin'){
                    $q = 'SELECT sum(flexpay_price) from product p join receipts r on p.code=r.productcode';
                 }elseif(yii::$app->user->identity->category == 'merchant'){
                    $q = 'SELECT sum(flexpay_price) from product p join receipts r on p.code=r.productcode where r.productcode like "'.yii::$app->user->identity->username.'%"';
                 }
                $sql = Yii::$app->db->createCommand($q);
                $count = $sql->queryScalar();

                $dataProvidersuccessful = new SqlDataProvider([
                   'sql' => $q,
                   'pagination' => [
                      'pageSize' => 30,
                    ],
                   'totalCount' => $count,
                ]);

                //Total value of active bookings

                if(yii::$app->user->identity->category == 'admin'){
                    $q = 'SELECT SUM( p.flexpay_price ) as total FROM product p 
                    JOIN customer c ON c.code = p.code';
                 }elseif(yii::$app->user->identity->category == 'merchant'){
                    $q = 'SELECT SUM(p.flexpay_price) as total FROM product p
                            JOIN customer c ON c.code = p.code
                            WHERE p.code LIKE  "'.yii::$app->user->identity->username.'%"';
                 }
                $command = Yii::$app->db->createCommand($q);
                $Tcount = $command->queryScalar();
                $data = $command->queryAll();

                return $this->render('index', [
                    'model'     => $model,
                    'active'    => $dataProviderActive,
                    'overdue'   => $dataProviderOverdue,
                    'Successful'=> $dataProvidersuccessful,
                    'activedata'=> $data[0]['total']
                ]);
        }else{
            return $this->render('index', [
                'model'     => $model,
            ]);
        }
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}

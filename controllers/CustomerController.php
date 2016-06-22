<?php

namespace app\controllers;

use Yii;
use app\models\Customer;
use app\models\SearchCustomer;
use app\models\Sms;
use app\models\Auth;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use yii\filters\AccessControl;
/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete','list'],
                'rules' => [
                    [
                        'allow' => true,
                        //'actions' => ['logout'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchCustomer();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionClients()
    {
        $usernames = Auth::find()->all();
        return $this->render('clients',[
            'usernames' => $usernames
        ]);
    }

    public function actionChangepassword()
    {
        $model = new Auth();
        $feedback = '';
        if(Yii::$app->request->post()){
            print_r($_POST);
            if(md5(Yii::$app->request->post()['Auth']['old_pass']) == yii::$app->user->identity->password){
                $user = Auth::findOne(yii::$app->user->identity->id);

                $new_pass = Yii::$app->request->post()['Auth']['new_pass'];

                if(($new_pass == Yii::$app->request->post()['Auth']['repeat_pass']) && (strlen($new_pass) >= 5)){
                    $user->password = md5(Yii::$app->request->post()['Auth']['new_pass']);
                    $user->save();
                    $feedback = "Password Changed Successfully";
                }
                elseif(strlen($new_pass) <= 5){
                    $feedback = "Minimum password should be atleast 5 characters";
                }else{
                    $feedback = "Password Mismatch";
                }
            }else{
                $feedback = "Wrong password";
            }
        }
        return $this->render('changepass',[
            'model' => $model,
            'feedback' => $feedback,
        ]);
    }
    
    public function actionRevoked()
    {
        //$searchModel = new SearchCustomer();
        //$dataProvider = $searchModel->searchactive(Yii::$app->request->queryParams);
        $model = new Sms();
        if(Yii::$app->request->post()){
            if(yii::$app->user->identity->category == 'admin'){
                $q = 'select c.*, p.*, sum(m.amount) ,p.flexpay_price - sum(m.amount) AS balance,
                 ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate, c.id as id, c.date as dated
                 from customer c
                 join product p on c.code = p.code
                 join mpesa m on c.phone = m.sender_phone
                 where m.status = 0 and ADDDATE(c.date,INTERVAL p.installments DAY) >= CURDATE()
                 and c.status = 1 
                 and c.phone = "'.$_POST['phone'].'"
                 group by m.sender_phone
                 ORDER BY dated DESC';
            }elseif(yii::$app->user->identity->category == 'merchant'){
                 $q = 'select c.*, p.*, sum(m.amount) ,p.flexpay_price - sum(m.amount) AS balance,
                 ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate, c.id as id, c.date as dated
                 from customer c
                 join product p on c.code = p.code
                 join mpesa m on c.phone = m.sender_phone
                 where m.status = 0 and ADDDATE(c.date,INTERVAL p.installments DAY) >= CURDATE()
                 and c.status = 1 
                 and c.code like "'.yii::$app->user->identity->username.'%"
                 and c.phone = "'.Yii::$app->request->post()['Sms']['phone'].'"
                 group by m.sender_phone
                 ORDER BY dated DESC';
            }
        }else{
            if(yii::$app->user->identity->category == 'admin'){
                $q = 'select c.*, p.*, sum(m.amount) ,p.flexpay_price - sum(m.amount) AS balance,
                 ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate, c.id as id, c.date as dated
                 from customer c
                 join product p on c.code = p.code
                 join mpesa m on c.phone = m.sender_phone
                 where m.status = 0 and ADDDATE(c.date,INTERVAL p.installments DAY) >= CURDATE()
                 and c.status = 1
                 group by m.sender_phone
                 ORDER BY dated DESC';
            }elseif(yii::$app->user->identity->category == 'merchant'){
                 $q = 'select c.*, p.*, sum(m.amount) ,p.flexpay_price - sum(m.amount) AS balance,
                 ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate, c.id as id, c.date as dated
                 from customer c
                 join product p on c.code = p.code
                 join mpesa m on c.phone = m.sender_phone
                 where m.status = 0 and ADDDATE(c.date,INTERVAL p.installments DAY) >= CURDATE()
                 and c.status = 1
                 and c.code like "'.yii::$app->user->identity->username.'%"
                 group by m.sender_phone
                 ORDER BY dated DESC';
            }
        }

        $result = Yii::$app->db->createCommand($q)->queryAll(); 
        $count = count($result);

        $dataProvider = new SqlDataProvider([
           'sql' => $q,
           'pagination' => [
              'pageSize' => 30,
            ],
           'totalCount' => $count,
        ]);

        return $this->render('revoked', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionActive()
    {
        //$searchModel = new SearchCustomer();
        //$dataProvider = $searchModel->searchactive(Yii::$app->request->queryParams);
        $model = new Sms();
        if(Yii::$app->request->post()){
            if(yii::$app->user->identity->category == 'admin'){
                $q = 'select c.*, p.*, sum(m.amount) ,p.flexpay_price - sum(m.amount) AS balance,
                 ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate, c.id as id, c.date as dated
                 from customer c
                 join product p on c.code = p.code
                 join mpesa m on c.phone = m.sender_phone
                 where m.status = 0 and ADDDATE(c.date,INTERVAL p.installments DAY) >= CURDATE()
                 and c.status = 0
                 and c.phone = "'.$_POST['phone'].'"
                 group by m.sender_phone
                 ORDER BY dated DESC';
            }elseif(yii::$app->user->identity->category == 'merchant'){
                 $q = 'select c.*, p.*, sum(m.amount) ,p.flexpay_price - sum(m.amount) AS balance,
                 ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate, c.id as id, c.date as dated
                 from customer c
                 join product p on c.code = p.code
                 join mpesa m on c.phone = m.sender_phone
                 where m.status = 0 and ADDDATE(c.date,INTERVAL p.installments DAY) >= CURDATE()
                 and c.status = 0
                 and c.code like "'.yii::$app->user->identity->username.'%"
                 and c.phone = "'.Yii::$app->request->post()['Sms']['phone'].'"
                 group by m.sender_phone
                 ORDER BY dated DESC';
            }
        }else{
            if(yii::$app->user->identity->category == 'admin'){
                $q = 'select c.*, p.*, sum(m.amount) ,p.flexpay_price - sum(m.amount) AS balance,
                 ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate, c.id as id, c.date as dated
                 from customer c
                 join product p on c.code = p.code
                 join mpesa m on c.phone = m.sender_phone
                 where m.status = 0 and ADDDATE(c.date,INTERVAL p.installments DAY) >= CURDATE()
                 and c.status = 0 
                 group by m.sender_phone
                 ORDER BY dated DESC';
            }elseif(yii::$app->user->identity->category == 'merchant'){
                 $q = 'select c.*, p.*, sum(m.amount) ,p.flexpay_price - sum(m.amount) AS balance,
                 ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate, c.id as id, c.date as dated
                 from customer c
                 join product p on c.code = p.code
                 join mpesa m on c.phone = m.sender_phone
                 where m.status = 0 and ADDDATE(c.date,INTERVAL p.installments DAY) >= CURDATE()
                 and c.status = 0 
                 and c.code like "'.yii::$app->user->identity->username.'%"
                 group by m.sender_phone
                 ORDER BY dated DESC';
            }
        }

        $result = Yii::$app->db->createCommand($q)->queryAll(); 
        $count = count($result);

        $dataProvider = new SqlDataProvider([
           'sql' => $q,
           'pagination' => [
              'pageSize' => 30,
            ],
           'totalCount' => $count,
        ]);

        return $this->render('active', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionOverdue()
    {
        $model = new Sms();
        if(Yii::$app->request->post()){
            if(yii::$app->user->identity->category == 'admin'){
                $q = 'select c.*, p.*, sum(m.amount) ,p.flexpay_price - sum(m.amount) AS balance,
                 ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate, c.date as dated
                 from customer c
                 join product p on c.code = p.code
                 join mpesa m on c.phone = m.sender_phone
                 where m.status = 0 and ADDDATE(c.date,INTERVAL p.installments DAY) <= CURDATE()
                 and c.phone = "'.$_POST['phone'].'"
                 group by m.sender_phone
                 ORDER BY dated DESC';
            }elseif(yii::$app->user->identity->category == 'merchant'){
                 $q = 'select c.*, p.*, sum(m.amount) ,p.flexpay_price - sum(m.amount) AS balance,
                 ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate, c.date as dated
                 from customer c
                 join product p on c.code = p.code
                 join mpesa m on c.phone = m.sender_phone
                 where m.status = 0 and ADDDATE(c.date,INTERVAL p.installments DAY) <= CURDATE()
                 and c.phone = "'.Yii::$app->request->post()['Sms']['phone'].'"
                 and c.code like "'.yii::$app->user->identity->username.'%"
                 group by m.sender_phone
                 ORDER BY dated DESC';
            }
        }else{
            if(yii::$app->user->identity->category == 'admin'){
                $q = 'select c.*, p.*, sum(m.amount) ,p.flexpay_price - sum(m.amount) AS balance,
                 ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate, c.date as dated
                 from customer c
                 join product p on c.code = p.code
                 join mpesa m on c.phone = m.sender_phone
                 where m.status = 0 and ADDDATE(c.date,INTERVAL p.installments DAY) <= CURDATE()
                 group by m.sender_phone
                 ORDER BY dated DESC';
            }elseif(yii::$app->user->identity->category == 'merchant'){
                 $q = 'select c.*, p.*, sum(m.amount) ,p.flexpay_price - sum(m.amount) AS balance,
                 ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate, c.date as dated
                 from customer c
                 join product p on c.code = p.code
                 join mpesa m on c.phone = m.sender_phone
                 where m.status = 0 and ADDDATE(c.date,INTERVAL p.installments DAY) <= CURDATE()
                 and c.code like "'.yii::$app->user->identity->username.'%"
                 group by m.sender_phone
                 ORDER BY dated DESC';
            }
        }
        
        //$sql = Yii::$app->db->createCommand($q);
        //$count = $sql->queryScalar();
        $result = Yii::$app->db->createCommand($q)->queryAll(); 
        $count = count($result);
        

        $dataProvider = new SqlDataProvider([
           'sql' => $q,
           'pagination' => [
              'pageSize' => 30,
            ],
           'totalCount' => $count,
        ]);

        return $this->render('overdue', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'result' => $result
        ]);
    }

    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionSendsms()
    {    
        $model = new Sms();
        if(yii::$app->user->identity->category == 'admin'){
            $phones = Customer::find()->select(['phone'])->all();
        }elseif (yii::$app->user->identity->category == 'merchant') {
            $phones = Customer::find()
                ->select(['phone'])
                ->where(['like','code' , yii::$app->user->identity->username])
                ->all();
        }
        //SELECT phone from customer where code like 'peter%'
        $feedback = "";

        if(Yii::$app->request->post()) {
            $username   = "peterKaranja";
            $apikey     = "f9dace446394374253e92a557042d8d15486dd51f9c86fd7a8530b8e416a463e";
            $gateway    = new AfricasTalkingGatewayController($username, $apikey);

            $from       = "20880";
            $message    = Yii::$app->request->post()['Sms']['sms'];
            //$message    = "Testing message";
            $rowcount   = count($phones); 
            $credit     = yii::$app->user->identity->sms_amount;           

            $messagelen=strlen($message);

            if($messagelen==0){
                $messagelen=0;
            }elseif($messagelen>0 && $messagelen<160){
                $messagelen=1;
            }elseif($messagelen>=160 && $messagelen<320 ){
                $messagelen=2;
            }elseif($messagelen>=320 && $messagelen<480 ){
                $messagelen=3;
            }elseif($messagelen>=480 && $messagelen<640){
                $messagelen=4;
            }elseif($messagelen>=640 && $messagelen<800){
                $messagelen=5;
            }
            $no_of_sms=($messagelen*$rowcount);
            $balance=($credit-$no_of_sms*5);

            if($messagelen==0){
                $rowcount=0;

                $feedback = "*** Please type the  message</font>";
            }

            if( $balance<=0 )
            {
                $rowcount=0;
                $feedback = " *** No enough credit to send  ".$no_of_sms." messages";;
            }
            else{
                $rowcount=$rowcount;
            }
            try 
            {               
                //Fetch Phone numbers
                $string = "";
                foreach ($phones as $key => $value) {       
                    $string .= $value->phone.",";
                }
                $recipients = rtrim($string,',');
                //String with numbers done

               $results = $gateway->sendMessage($recipients, $message, $from);            
                foreach($results as $result) {
                    $feedback .= "<br>Message Sending successful";                    
                }
                

                //UPDATE BALANCE
                $user = Auth::find()->where(['username' => yii::$app->user->identity->username])->one();;
                $user->sms_amount = $balance;
                $user->update();
            }
            catch (AfricasTalkingGatewayException $e )
            {
                $feedback = $e."Some technical problem occured";
            }
            //$feedback = $recipients;
        }
        
        return $this->render('sendsms', [
                'model' => $model,
                'feedback'=> $feedback,                
        ]);
        
    }


    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /*
        $model = new Customer();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
        */
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {        
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        /*
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
        */
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
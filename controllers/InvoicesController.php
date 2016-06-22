<?php

namespace app\controllers;

use Yii;
use app\models\Invoices;
use app\models\Auth;
use app\models\InvoicesSeacrh;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\models\Mpesa;

/**
 * InvoicesController implements the CRUD actions for Invoices model.
 */
class InvoicesController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete,createinvoice' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Invoices models.
     * @return mixed
     */

    public function actionCreateinvoice($amount,$user_id){
        //print_r(Yii::$app->request->get());
        $data = Yii::$app->request->get();
        $invoice = new Invoices();
        $invoice->user_id = yii::$app->user->identity->id;
        $invoice->user_id_to = $data['user_id'];
        $invoice->amount = $data['amount'];
        $invoice->for_the_month = $data['for_the_month'];
        $invoice->save(false);
        //print_r($invoice);
        return $this->redirect(['invoices','id'=>$data['user_id']]);
    }

    public function actionPrint($amount,$user_id,$year,$month){
        $data = Yii::$app->request->get();
        if(yii::$app->user->identity->id == $data['user_id']){
        	$record = Invoices::find()
	            ->where("(user_id_to = ".$data['user_id'].") 
	                            OR 
	                     (user_id = ".$data['user_id'].")
	                     AND 
	                     for_the_month =".$data['year']."-".$data['month']."-00")
	                     
	            ->one();
        }else{
        	$record = Invoices::find()
	            ->where("(user_id_to = ".$data['user_id'].") OR 
	                     (user_id_to = ".yii::$app->user->identity->id.") 
	                            and 
	                     (user_id = ".$data['user_id'].") OR 
	                     (user_id = ".yii::$app->user->identity->id.")
	                     AND 
	                     for_the_month =".$data['year']."-".$data['month']."-00")
	            ->one();
        }
        
        $user = Auth::find()
            ->where(['id'=> $data['user_id']])
            ->one();

        $MpesaRecord = Mpesa::find()
            ->where("product_code LIKE '".$user->username."%'
                AND transaction_timestamp LIKE '".$data['year']."-".$data['month']."%'")
            ->all();

        $q = 'select * from mpesa where product_code LIKE "'.$user->username.'%"
                AND transaction_timestamp LIKE  "'.$data['year']."-".$data['month'].'%"';
        $sql = Yii::$app->db->createCommand($q)->queryAll();
        $count = count($sql);

        $dataProvider = new SqlDataProvider([
               'sql' => $q,
               'pagination' => [
                  'pageSize' => 500,
                ],
               'totalCount' => $count,
        ]);
        
        return $this->render('print', [
                'dataProvider' => $dataProvider,
                'month' => $data['month'],
                'year' => $data['year'],
                'details' => $record,
            ]);
    }

    public function actionInvoices($id)
    {
        $searchModel = new InvoicesSeacrh();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(yii::$app->user->identity->category == 'admin'){
            $merchant = Auth::findOne($id);
            $q = 'select sum(m.amount) as total ,p.flexpay_price - sum(m.amount) AS balance,
                     ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate,
                     YEAR( m.transaction_timestamp ) as yearg, MONTH( m.transaction_timestamp ) as monthg 
                     from customer c
                     join product p on c.code = p.code
                     join mpesa m on c.phone = m.sender_phone
                     where m.status = 0
                     and c.code like "'.$merchant->username.'%"
                     GROUP BY YEAR( m.transaction_timestamp ) , MONTH( m.transaction_timestamp ) DESC 
                    ';
            $sql = Yii::$app->db->createCommand($q)->queryAll();
            $count = count($sql);
            $session = Yii::$app->session;
            $session->set('user_idt', $id);

            $dataProvider = new SqlDataProvider([
                   'sql' => $q,
                   'pagination' => [
                      'pageSize' => 30,
                    ],
                   'totalCount' => $count,
                ]);
            
            return $this->render('invoices', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'id' => $id,
            ]); 
            
        }
    }


    public function actionIndex()
    {
        $searchModel = new InvoicesSeacrh();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if(yii::$app->user->identity->category == 'merchant'){
            $q = 'select sum(m.amount) as total ,p.flexpay_price - sum(m.amount) AS balance,
                     ADDDATE(c.date,INTERVAL p.installments DAY) AS DueDate,
                     YEAR( m.transaction_timestamp ) as yearg, MONTH( m.transaction_timestamp ) as monthg 
                     from customer c
                     join product p on c.code = p.code
                     join mpesa m on c.phone = m.sender_phone
                     where m.status = 0
                     and c.code like "'.yii::$app->user->identity->username.'%"
                     GROUP BY YEAR( m.transaction_timestamp ) , MONTH( m.transaction_timestamp ) DESC 
                    ';
            $sql = Yii::$app->db->createCommand($q)->queryAll();
            $count = count($sql);

            $dataProvider = new SqlDataProvider([
                   'sql' => $q,
                   'pagination' => [
                      'pageSize' => 30,
                    ],
                   'totalCount' => $count,
                ]);
            
            return $this->render('invoices', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]); 
            
        }elseif(yii::$app->user->identity->category == 'admin'){
            $small_merchants = new ActiveDataProvider([
                'query' => Auth::find()->where(['till_no' => '212352']),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

            $big_merchants = new ActiveDataProvider([
                'query' => Auth::find()->where(['not in','till_no','212352']),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
            
            return $this->render('index', [
                'searchModel' => $searchModel,
                'big_merchants' => $big_merchants,
                'small_merchants' => $small_merchants,
            ]);
        }               
    }

    public function actionList($id){
        $dataProvider = new ActiveDataProvider([
            'query' => Invoices::find()->where(['user_id'=> $id]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        $searchModel = new InvoicesSeacrh();
        return $this->render('list', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Displays a single Invoices model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Invoices model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Invoices();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Invoices model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Invoices model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Invoices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invoices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoices::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

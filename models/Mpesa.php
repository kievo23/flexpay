<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mpesa".
 *
 * @property string $transaction_reference
 * @property string $transaction_timestamp
 * @property string $sender_phone
 * @property string $first_name
 * @property string $last_name
 * @property integer $amount
 * @property integer $status
 */
class Mpesa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mpesa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transaction_reference', 'transaction_timestamp', 'sender_phone', 'first_name', 'last_name', 'amount', 'status'], 'required'],
            [['transaction_timestamp'], 'safe'],
            [['amount', 'status'], 'integer'],
            [['transaction_reference'], 'string', 'max' => 13],
            [['sender_phone'], 'string', 'max' => 14],
            [['first_name'], 'string', 'max' => 12],
            [['last_name'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transaction_reference' => 'Transaction Reference',
            'transaction_timestamp' => 'Transaction Timestamp',
            'sender_phone' => 'Sender Phone',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'amount' => 'Amount',
            'status' => 'Status',
            'product_code' => 'Product Code'
        ];
    }
}

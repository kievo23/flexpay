<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoices".
 *
 * @property integer $id
 * @property string $user_id
 * @property string $amount
 * @property string $for_the_month
 * @property string $collected
 */
class Invoices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_id_to', 'amount', 'for_the_month', 'collected'], 'required'],
            [['user_id', 'user_id_to', 'amount', 'for_the_month', 'collected'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'user_id_to' => 'User ID TO',
            'amount' => 'Amount',
            'for_the_month' => 'For The Month',
            'collected' => 'Collected',
        ];
    }
}

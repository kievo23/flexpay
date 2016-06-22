<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property integer $id
 * @property string $phone
 * @property string $status
 * @property string $code
 * @property string $date
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'code', 'date'], 'required'],
            [['phone'], 'string', 'max' => 14],
            [['status'], 'string', 'max' => 11],
            [['code'], 'string', 'max' => 13],
            [['date'], 'string', 'max' => 22]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'status' => 'Status',
            'code' => 'Code',
            'date' => 'Date',
        ];
    }

    public function getProduct(){
        return $this->hasone(Product::className(), ['code'=>'code']);
    }
}

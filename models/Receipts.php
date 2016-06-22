<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "receipts".
 *
 * @property integer $status
 * @property string $phone
 * @property string $receipt_no
 * @property string $productcode
 * @property integer $id
 * @property integer $extra
 */
class Receipts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'receipts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'phone', 'receipt_no', 'productcode', 'extra'], 'required'],
            [['extra'], 'integer'],
            [['phone'], 'string', 'max' => 14],
            [['receipt_no'], 'string', 'max' => 20],
            [['productcode'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'status' => 'Status',
            'phone' => 'Phone',
            'receipt_no' => 'Receipt No',
            'productcode' => 'Productcode',
            'id' => 'ID',
            'extra' => 'Extra',
        ];
    }

    public function getProduct(){
        return $this->hasone(Product::className(), ['code'=>'productcode']);
    }
}

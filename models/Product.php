<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property string $code
 * @property string $name
 * @property string $source
 * @property integer $price
 * @property integer $flexpay_price
 * @property integer $book_amount
 * @property integer $installments
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'source', 'price', 'flexpay_price', 'book_amount', 'installments'], 'required'],
            [['price', 'flexpay_price', 'book_amount', 'installments'], 'integer'],
            [['code', 'name'], 'string', 'max' => 22],
            [['source'], 'string', 'max' => 44]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Code',
            'name' => 'Name',
            'source' => 'Source',
            'price' => 'Price',
            'flexpay_price' => 'Flexpay Price',
            'book_amount' => 'Book Amount',
            'installments' => 'No of days',
        ];
    }
}

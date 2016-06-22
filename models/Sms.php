<?php

namespace app\models;

use Yii;
use yii\base\Model;
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
class Sms extends Model
{
    /**
     * @inheritdoc
     */
    public $sms;
    public $phone;   
}

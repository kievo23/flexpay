<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auth".
 *
 * @property integer $id
 * @property string $empno
 * @property string $firstName
 * @property string $lastName
 * @property string $username
 * @property string $password
 * @property string $category
 * @property integer $counter
 * @property integer $sms_amount
 */
class Auth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $old_pass;
    public $new_pass;
    public $repeat_pass;

    public static function tableName()
    {
        return 'auth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['empno', 'firstName', 'lastName', 'username', 'password', 'category', 'sms_amount', 'till_no','interest_rate'], 'required'],
            [[ 'sms_amount','till_no','interest_rate'], 'integer'],
            [['empno'], 'string', 'max' => 40],
            [['firstName', 'lastName', 'username', 'category'], 'string', 'max' => 20],
            [['password'], 'string', 'max' => 255],
            [['username'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'empno' => 'Empno',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'username' => 'Username',
            'password' => 'Password',
            'category' => 'Category',
            'sms_amount' => 'Sms Amount',
            'old_pass' => 'Old Password',
            'new_pass' => 'New Password',
            'repeat_pass' => 'Repeat Password',
            'till_no' => 'Till Number',
            'interest_rate' => 'Interest Rate',
        ];
    }
}

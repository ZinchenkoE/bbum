<?php

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\Response;

/**
 * Class User
 * @package app\models\user
 * @property integer $id
 * @property string  $email
 * @property string  $customer_name
 * @property string  $phone
 * @property string  $auth_key
 */

class Customer extends ActiveRecord
{
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

}

<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\Response;

/**
 * Class User
 * @package app\models\user
 * @property integer $customer_id
 * @property string  $email
 * @property string  $customer_name
 * @property string  $phone
 * @property string  $auth_key
 */

class Customer extends ActiveRecord
{
    public static function tableName() { return 'bs_customer'; }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    public function getId()
    {
        return $this->user_id;
    }

}

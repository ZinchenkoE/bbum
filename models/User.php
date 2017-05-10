<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\Response;

/**
 * Class User
 * @package app\models\user
 * @property integer $user_id
 * @property string  $email
 * @property string  $username
 * @property string  $phone
 * @property string  $auth_key
 */

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName() { return 'bs_user'; }
    public static function findIdentity($id) { return static::findOne($id); }
    public static function findIdentityByAccessToken($token, $type = null){}
    public static function findByEmail($email) { return static::findOne(['email' => $email]); }
    public function getId() { return $this->user_id; }
    public function getAuthKey() { return $this->auth_key; }
    public function validateAuthKey($password) {
        return Yii::$app->getSecurity()->validatePassword($password, $this->auth_key);
    }

    public static function loginPost(){
        $email    = Yii::$app->request->post('email');
        $password = Yii::$app->request->post('password');
        $user = self::findByEmail($email);
        if($user && $user->validateAuthKey($password)){
            Yii::$app->user->login($user, 3600 * 24 * 30);
            Yii::$app->controller->redirect('/admin', 301);
        } else {
            Yii::$app->response->statusCode = (int)400;
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data['backData']['errors']['password'] = ['Неверный логин или пароль'];
        }
    }

    public static function createUser($email, $username, $phone, $auth_key) {
        $user = static::findByEmail('admin@ww.ww');
        $user->auth_key   = Yii::$app->getSecurity()->generatePasswordHash('admin@ww.ww');
        $user->save();
    }
}

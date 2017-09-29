<?php
namespace app\models;
use Yii;
use yii\base\Object;
use \yii\web\IdentityInterface;
use yii\web\Response;

class User extends Object implements IdentityInterface
{
    public $id;
    public $login;
    public $password;
    public $authKey;
    public $accessToken;
    public static $users;

    public static function findIdentity($id)
    {
        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }
        return null;
    }

    public static function findByLogin($login)
    {
        foreach (self::$users as $user) {
            if ($user['login'] === $login) {
                return new static($user);
            }
        }
        return null;
    }

    public function getId() { return $this->id; }

    public function getAuthKey() { return $this->authKey; }

    public function validateAuthKey($authKey) { return $this->authKey === $authKey; }

    public function validatePassword($password) { return $this->password === $password; }

    public static function loginPost() {
        $login    = Yii::$app->request->post('email');
        $password = Yii::$app->request->post('password');
        $user = self::findByLogin($login);
        if($user && $user->validatePassword($password)){
            Yii::$app->user->login($user, 3600 * 24 * 30);
            return Yii::$app->controller->redirect('/admin', 301);
        } else {
            Yii::$app->response->statusCode = (int)400;
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data['backData']['error']['password'] = ['Неверный логин или пароль'];
            return false;
        }
    }
}

User::$users = Yii::$app->params['users'];

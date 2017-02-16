<?php
namespace app\models;

use Yii;
use yii\base\Object;
use \yii\web\IdentityInterface;
use yii\web\Response;

class User extends Object implements IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin@ww.ww',
            'password' => 'admin@ww.ww',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ]
    ];

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

    /**
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    public static function loginPost() {
        $post = Yii::$app->request->post();
        $user = self::findByUsername($post['email']);
        if($user && $user->validatePassword($post['password'])){
            Yii::$app->user->login($user, 3600 * 24 * 30);
            return Yii::$app->controller->redirect('/admin', 301);
        } else {
            Yii::$app->response->statusCode = (int)400;
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data['backData']['error']['password'] = ['Неверный логин или пароль'];
        }
    }
}

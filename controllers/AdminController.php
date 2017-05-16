<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use app\components\helpers\Logger;
use app\models\Product;
use app\models\Category;
use app\models\User;
use app\models\Order;

class AdminController extends Controller
{
    public $layout = 'admin';

    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest && Yii::$app->request->url !== '/login')
            return $this->redirect('/login', 301);
        return parent::beforeAction($action);
    }

    public function afterAction($action, $result)
    {
        if(Yii::$app->request->isGet) Url::remember();
        return parent::afterAction($action, $result);
    }

    public function actionLogJs()
    {
        if(!Yii::$app->request->isAjax) return $this->redirect('/login', 301);
        Logger::logJs(Yii::$app->request->post('var'));
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->grest->setCode(301, null, '/admin');
        }
        if (Yii::$app->request->isGet){
            return $this->render('login');
        }
        if (Yii::$app->request->isPost){
            User::loginPost();
        }
        return null;
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect('/login', 301);
    }

    public function actionIndex()
    {
        Order::initModel()->run();
        return Yii::$app->grest->render();
    }

    public function actionProduct($key = null, $id = null)
    {
        Product::initModel()->run($key, $id);
        return Yii::$app->grest->render();
    }

    public function actionCategory($key = null, $id = null)
    {
        Category::initModel()->run($key, $id);
        return Yii::$app->grest->render();
    }

    public function actionSetting()
    {
        Yii::$app->grest->render = 'settings/setting';
        return Yii::$app->grest->render();
    }
}

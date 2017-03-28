<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\Category;
use app\models\User;
use yii\web\Controller;

class AdminController extends Controller
{
    public $layout = 'admin';

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
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect('/login', 301);
    }

    public function actionIndex()
    {
        Yii::$app->grest->render = 'index';
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

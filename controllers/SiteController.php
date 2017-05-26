<?php
namespace app\controllers;

use app\models\City;
use app\models\User;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use app\components\helpers\Logger;
use app\models\Order;
use app\models\SiteIndex;
use app\models\SiteProduct;
use app\models\SiteCategory;

class SiteController extends Controller
{
    public $layout = 'site';

    public function beforeAction($action)
    {
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
        return null;
    }

    public function actionIndex()
    {
        SiteIndex::initModel()->run();
        return Yii::$app->grest->render();
    }

    public function actionInfo()
    {
        Yii::$app->grest->render = 'info';
        return Yii::$app->grest->render();
    }

    public function actionContacts()
    {
        Yii::$app->grest->render = 'contacts';
        return Yii::$app->grest->render();
    }

    public function actionCategory($key = null, $id = null)
    {
        SiteCategory::initModel()->run($key, $id);
        return Yii::$app->grest->render();
    }

    public function actionProduct($key = null, $id = null)
    {
        SiteProduct::initModel()->run($key, $id);
        return Yii::$app->grest->render();
    }

    public function actionOrder($key = null, $id = null)
    {
        Order::initModel()->run($key, $id);
        return Yii::$app->grest->render();
    }

//    public function actionError()
//    {
//        echo 'Произошла ошибка сервера!';
////        return Yii::$app->grest->render();
//    }
}

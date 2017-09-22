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
use yii\web\Response;

class SiteController extends Controller
{
    public $layout = 'site';
    public $products = [];

    public $meta = [
        'title'       => 'Baby shop',
        'description' => 'Магазин детской одежды Baby shop',
        'abstract'    => 'Магазин детской одежды Baby shop'
    ];
    public $view;
    public $data = [];
    public $bd   = [];

    public function beforeAction($action)
    {
        $this->view = $this->action->id;
        return parent::beforeAction($action);
    }

    public function afterAction($action, $result)
    {
        if(Yii::$app->request->isGet) Url::remember();
        return parent::afterAction($action, $result);
    }

    private function xrender () {
        $req = Yii::$app->request;
        $res = Yii::$app->response;
        if ($req->isAjax){
            $res->format = Response::FORMAT_JSON;
            if ($req->isGet){
                $res->data['renders']['main'] = [
                    'render' => $this->renderPartial($this->view, ['data' => $this->data]),
                    'type'   => 'rp'
                ];
            }
            if ($this->bd) $res->data['bd'] = $this->bd;

            return $res;
        } else {
            return $this->render($this->view, ['data' => $this->data]);
        }
    }

    public function actionLogJs()
    {
        if(!Yii::$app->request->isAjax) return $this->redirect('/login', 301);
        Logger::logJs(Yii::$app->request->post('var'));
        return null;
    }

    public function actionIndex()
    {
        return $this->xrender();
    }

    public function actionInfo()
    {
        return $this->xrender();
    }

    public function actionContacts()
    {
        return $this->xrender();
    }

    public function actionCategory($key = null, $id = null)
    {
        SiteCategory::initModel()->run($key, $id);
        return $this->xrender();
    }

    public function actionProduct($key = null, $id = null)
    {
        SiteProduct::initModel()->run($key, $id);
        return $this->xrender();
    }

    public function actionOrder($key = null, $id = null)
    {
        Order::initModel()->run($key, $id);
        return $this->xrender();
    }

}


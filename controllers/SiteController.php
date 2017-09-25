<?php
namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use app\components\helpers\Logger;
use yii\web\Response;

class SiteController extends Controller
{
    public $layout = 'site';
    public $menu;
    public $categories;
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
        $this->categories = 55;
        $this->menu;
//        $category = Yii::$app->db->createCommand("
//            SELECT pc.*,  c.*, c.parent_id, c.category_id, COUNT(p.product_id) AS count_product
//            FROM bs_category c
//            LEFT JOIN bs_parent_category pc ON c.parent_id = pc.parent_category_id
//            INNER JOIN bs_product p ON p.category = c.category_id
//            GROUP BY c.category_id
//            ORDER BY c.category_title_ru")->queryAll();

//        $category_for_boy  = array_filter($category, function($i) { return ($i['parent_id'] == 1); });
//        $category_for_girl = array_filter($category, function($i) { return ($i['parent_id'] == 2); });
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


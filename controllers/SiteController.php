<?php
namespace app\controllers;

use Yii;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use app\components\helpers\Logger;
use app\models\Product;
use app\models\Category;


/**
 * @property \app\models\Category $root_categories
 */

class SiteController extends Controller
{
    public $layout = 'site';
    public $root_categories;
    public $view;
    public $data = [];
    public $bd   = [];
    public $meta = [
        'title'       => 'Baby shop',
        'description' => 'Магазин детской одежды Baby shop',
        'abstract'    => 'Магазин детской одежды Baby shop'
    ];

    public function beforeAction($action)
    {
        $this->view = $this->action->id;
        $this->root_categories = Category::getRootCategory();

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
        if(!Yii::$app->request->isAjax) return;
        Logger::logJs(Yii::$app->request->post('var'));
        return null;
    }

    public function actionIndex()
    {
//        echo '<pre>'; var_dump($this->root_categories[0]->children); die;
        $this->data['product_recommend'] = Product::findAll(['recommended' =>  1]);
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

//    public function actionCategory(
//        string $key, int $page = 1, $perpage = 20, int $price_from = 0, int $price_to, string $search, string $sort = 'id'
//    )
    public function actionCategory()
    {
        $req  = Yii::$app->request;
        $cat  = $req->get('cat');
        $sort = $req->get('sort');
        $query = Product::find()
            ->where([
                'status' => Product::STATUS_ACTIVE,
                'category_id' => $cat
            ]);
        $countQuery    = clone $query;
        $minPriceQuery = clone $query;
        $maxPriceQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $products = $query
            ->offset($pages->offset)
            ->orderBy($sort)
            ->limit($pages->limit)
            ->all();

        $this->data = [
            'pages'     => $pages,
            'products'  => $products,
            'min_price' => $minPriceQuery->min('price'),
            'max_price' => $maxPriceQuery->max('price'),
        ];
        return $this->xrender();
    }

    public function actionProduct(int $id)
    {
        $this->data['product'] = Product::findOne($id);
        return $this->xrender();
    }

    public function actionOrder()
    {
        return $this->xrender();
    }

}


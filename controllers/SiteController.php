<?php
namespace app\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use app\components\helpers\Logger;
use app\models\Product;
use app\models\Category;


/**
 * @property \app\models\Category $root_categories
 */

class SiteController extends Controller
{
    public $layout = 'site';
    public $meta = [
        'title'       => 'Baby shop',
        'description' => 'Магазин детской одежды Baby shop',
        'abstract'    => 'Магазин детской одежды Baby shop'
    ];

    use ControllerTrait;

    public function beforeAction($action)
    {
        $this->view = $this->action->id;
        return parent::beforeAction($action);
    }

    /** ___________________________________________________________________________________________________________ */
    public function actionIndex()
    {
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


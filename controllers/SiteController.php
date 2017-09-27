<?php
namespace app\controllers;
use Yii;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use app\components\Logger;
use app\models\Product;
use app\models\Category;
use app\models\Customer;
use app\models\Order;


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
//        $user = new Customer();
//        $user->email         = 'sfdsdf';
//        $user->save();
        echo '<pre>'; var_dump(Customer::findOne(['phone' => '+38 (034) 534 53 77'])); die;
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

        $cat_arr = array_map(function ($v){ return $v->id; }, Category::findOne($cat)->getChildren());
        array_push($cat_arr, $cat);

        $query = Product::find()
            ->where([
                'status' => Product::STATUS_ACTIVE,
                'category_id' => $cat_arr
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

    public function actionCreateOrder()
    {
        $order = new Order();
        if ($order->create(Yii::$app->request->post())) {
            return $this->setCode(302, 'S::Ваш заказ успешно оформлен.', Url::previous());
        } else {
            return $this->setCode(499, 'Ошибка при обработке заказа');
        }
    }

}


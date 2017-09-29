<?php
namespace app\controllers;
use Yii;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use app\components\Logger;
use app\models\Product;
use app\models\Category;
use app\models\User;
use app\models\Order;

class AdminController extends Controller
{
    public $layout = 'admin';

    use ControllerTrait;

    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest && Yii::$app->request->url !== '/login')
            return $this->redirect('/login', 301);
        $this->view = $this->action->id;
        return parent::beforeAction($action);
    }

    /** ___________________________________________________________________________________________________________ */

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) return $this->redirect('/login', 301);
        if (Yii::$app->request->isGet) return $this->render('login');
        if (Yii::$app->request->isPost) User::loginPost();
        return null;
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect('/login', 301);
    }

    public function actionIndex()
    {
//        Product::saveProduct(['title_ru' => 'test', 'description_ru' => 'testtest', 'price' => 55]);
//
        $req   = Yii::$app->request;
        $sort  = $req->get('sort');
        $query = Order::find();
        $countQuery    = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $orders = $query
            ->offset($pages->offset)
            ->orderBy($sort)
            ->limit($pages->limit)
            ->all();

        $this->data = [
            'pages'     => $pages,
            'orders'    => $orders,
        ];
        return $this->xrender();
    }

    public function actionOrder()
    {
        return $this->xrender();
    }

    public function actionProductsTable()
    {
        $req    = Yii::$app->request;
        $search = $req->get('search', '');
        $sort   = $req->get('sort');

        $query = Product::find()
            ->where(['LIKE', 'title_ru', $search]);
        $countQuery    = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $products = $query
            ->offset($pages->offset)
            ->orderBy($sort)
            ->limit($pages->limit)
            ->all();

        $this->data = [
            'pages'     => $pages,
            'products'  => $products,
        ];
        return $this->xrender();
    }
    public function actionProduct(int $id)
    {
        $this->data['product'] = Product::findOne($id) || new Product();
        return $this->xrender();
    }

    public function actionCategoriesTable()
    {
        $this->data['parent_categories'] = Category::getRootCategory();
        return $this->xrender();
    }

    public function actionCategory(string $id)
    {
        Yii::$app->request->isGet ? Category::get() : Category::post();
        return $this->xrender();
    }

    public function actionSetting()
    {
        return $this->xrender();
    }
}

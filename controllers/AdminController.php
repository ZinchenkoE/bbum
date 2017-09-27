<?php
namespace app\controllers;
use Yii;
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
        if (!Yii::$app->user->isGuest)  return $this->redirect('/login', 301);
        if (Yii::$app->request->isGet)  return $this->render('login');
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
        return $this->xrender();
    }

    public function actionOrder()
    {
        return $this->xrender();
    }

    public function actionProductTable()
    {
        $this->data['products'] = Product::find()->all();
        return $this->xrender();
    }
    public function actionProductAction(int $id)
    {
        $this->data['product'] = Product::findOne($id);
        return $this->xrender();
    }

    public function actionCategoryTable()
    {
        $this->data['parent_categories'] = Category::getRootCategory();
        return $this->xrender();
    }

    public function actionCategoryAction(string $id)
    {
        return $this->xrender();
    }

    public function actionSetting()
    {
        return $this->xrender();
    }
}

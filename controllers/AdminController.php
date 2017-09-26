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
use yii\web\Response;

class AdminController extends Controller
{
    public $layout = 'admin';
    public $view;
    public $data = [];
    public $bd   = [];

    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest && Yii::$app->request->url !== '/login')
            return $this->redirect('/login', 301);
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

    public function setCode(int $code = 200, string $message = '', $url = false)
    {
        Yii::$app->response->statusCode    = $code;
        Yii::$app->response->data['flash'] = $message;
        if (301 == $code) Yii::$app->session->setFlash('error', $message);
        if ($url){
            if (Yii::$app->request->isAjax){
                Yii::$app->response->headers->set('X-Redirect', (string)$url);
            } else {
                $this->redirect($url, $code);
            }
        }
        return null;
    }

    public function actionLogJs()
    {
        if(!Yii::$app->request->isAjax) return;
        Logger::logJs(Yii::$app->request->post('var'));
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->setCode(301, null, '/admin');
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
        return $this->xrender();
    }

    public function actionOrder()
    {
        return $this->xrender();
    }

    public function actionProduct(int $id)
    {
        $this->data['product'] = Product::findOne($id);
        return $this->xrender();
    }

    public function actionCategoryTable()
    {
        $this->data['parent_categories'] = Category::getRootCategory();
        return $this->xrender();
    }

    public function actionActionCategory(string $id)
    {
        return $this->xrender();
    }

    public function actionSetting()
    {
        return $this->xrender();
    }
}

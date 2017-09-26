<?php
namespace app\controllers;
use Yii;
use app\components\helpers\Logger;
use yii\helpers\Url;
use yii\web\Response;

trait ControllerTrait
{
    public $view;
    public $data = [];
    public $bd   = [];

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

    public function afterAction($action, $result)
    {
        if(Yii::$app->request->isGet) Url::remember();
        return parent::afterAction($action, $result);
    }

}

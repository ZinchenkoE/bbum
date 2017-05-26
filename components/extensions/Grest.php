<?php

namespace app\components\extensions;

use Yii;
use yii\web\Response;

define('RP', 'rp');
define('PP', 'pp');
define('AP', 'ap');


class Grest
{
    public $meta = [
        'title'       => 'Baby shop',
        'description' => 'Магазин детской одежды Baby shop',
        'abstract'    => 'Магазин детской одежды Baby shop'
    ];
    public $isMeta = true;
    public $render = 'index';
    public $data=[];
    public $renders=[];
    public $backData=[];

    public function setCode(int $code = 200, string $message = '', $url = false)
    {
        Yii::$app->response->statusCode = $code;
        Yii::$app->response->data['flash'] = $message;
        if (301 == $code){
            Yii::$app->session->setFlash('error', $message);
        }
        if ($url){
            if (Yii::$app->request->isAjax){
                Yii::$app->response->headers->set('X-Redirect', (string)$url);
            } else {
                Yii::$app->controller->redirect($url, $code);
            }
        }
        return null;
    }

    public function render()
    {
        if ($this->isMeta && is_array($this->meta) && $this->meta){
            Yii::$app->response->data['meta'] = $this->meta;
        }
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if(Yii::$app->request->get('get-data-as') == 'json'){
                Yii::$app->response->data = $this->data;
                return Yii::$app->response;
            }
            if ($this->render != 'empty' && Yii::$app->request->isGet){
                Yii::$app->response->data['renders']['main']['render'] = Yii::$app->controller->renderPartial($this->render,['data'=>$this->data]);
                Yii::$app->response->data['renders']['main']['type'] = RP;
            }
            if (is_array($this->renders) && $this->renders){
                foreach ($this->renders as $key => $render){
                    Yii::$app->response->data['renders'][$key]['render'] = Yii::$app->controller->renderPartial($render['render'],['data' => $render['data']]);
                    Yii::$app->response->data['renders'][$key]['type'] = $render['type'] ? $render['type'] : RP;
                }
            }
            if (is_array($this->backData) && $this->backData){
                Yii::$app->response->data['backData'] = $this->backData;
            }
            return Yii::$app->response;
        }
        else return Yii::$app->controller->render($this->render,['data'=>$this->data]);
    }   

}
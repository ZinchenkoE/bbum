<?php
namespace app\components\traits;

use yii\web\BadRequestHttpException;

trait ModelTrait
{
    /**
     * @var \yii\db\Connection
     */
    private $db;
   
    /**
     * @var \app\components\extensions\Grest
     */
    private $grest;

    public function __construct()
    {
        $this->db = &\Yii::$app->db;        
        $this->grest = &\Yii::$app->grest;
    }

    public function __destruct()
    {
        $this->db->close();
    }

    public static function initModel()
    {
        return new static();
    }

    public function run($key, $id)
    {
        if (!\Yii::$app->request->isAjax && !\Yii::$app->request->isGet){
            throw new BadRequestHttpException('Request type denided');
        }

        $callMethod = 'get';

        if (\Yii::$app->request->isAjax){
            if (\Yii::$app->request->isPost){
                if (\Yii::$app->request->post('_rm')){
                    $callMethod = \Yii::$app->request->post('_rm');
                } else $callMethod = 'post';
            }
        }
        if (in_array($callMethod,['get','post','create','remove','put']) && method_exists($this, $callMethod)){
            $this->$callMethod($key, $id);
        } else {
            throw new BadRequestHttpException('Method not found');
        };

    }

    protected function post()
    {
        if (\Yii::$app->request->post('_prm')) {
            $callMethod = (\Yii::$app->request->post('_prm'))."Post";
            if (method_exists($this, $callMethod)){
                $this->$callMethod();
            } else {
                throw new BadRequestHttpException('Post method not found');
            }
        } else {
            throw new BadRequestHttpException('A post request is not defined');
        }
    }

    protected function notFormValid($errors = false)
    {
        $this->grest->setCode(500, null);
        $this->grest->backData['error'] = $errors;
    }

}
<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\components\traits\ModelTrait;


class Order extends Model
{
    const STATUS_NEW        = 0;
    const STATUS_CONFIRMED  = 1;
    const STATUS_DELETED    = 2;

    public $title_ru;

    use ModelTrait;

    public function rules()
    {
        return [];
    }

    protected function get($key)
    {
    }

    protected function create()
    {
    }

    protected function put($key)
    {
    }

    protected function remove($key)
    {
    }

    public static function getOrderById($id)
    {
        return Yii::$app->db->createCommand("SELECT * FROM bs_order WHERE order_id = {$id}")->queryOne();
    }

}
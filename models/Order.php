<?php
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use app\components\Logger;

/**
 * Class Order
 * @package app\models
 * @property integer  $id
 * @property integer  $customer_id
 * @property integer  $status
 * @property integer  $delivery_id
 * @property integer  $city_name
 * @property string   $stock
 * @property string   $products
 * @property integer  $total_price
 * @property integer  $create_time
 * @property integer  $update_time
 */

class Order extends ActiveRecord
{
    const STATUS_NEW        = 0; // новый
    const STATUS_CONFIRMED  = 1; // принятый
    const STATUS_DONE       = 2; // завершенный

    public $products;
    public $quantity;

    public $email;
    public $phone;
    public $customer_name;

    public function scenarios()
    {
        return [
            'create' => [
                'products', 'quantity', 'customer_id', 'email', 'customer_name', 'phone', 'delivery_id', 'city', 'stock'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                ['customer_id', 'status', 'delivery_id'], 'integer'
            ],
            [
                ['products', 'quantity'], 'each', 'rule' => ['integer']
            ],
            [
                ['products', 'quantity', 'customer_name', 'phone', 'delivery_id', 'city', 'stock'],
                'required', 'message' => 'Поле обязательно для заполнения', 'on'=>'create'
            ],
            [
                ['customer_name', 'phone', 'city', 'stock'], 'string', 'max' => 100,
                'tooLong' => 'Длинна не более 100 символов'
            ],

        ];
    }



    private function sendMail()
    {
        try{
            Yii::$app->mailer->compose()
                ->setFrom('sale@baby-bum.in.ua')
                ->setTo('zinchenko.evgeniy@gmail.com')
                ->setSubject('Оформлен новый заказ!')
                ->setHtmlBody('<b>текст сообщения в формате HTML</b>')
                ->send();

        }catch(Exception $e){
            Logger::logException($e, 'Ошибка при отправке письма');
        }
    }

    public function create($data) {

    }
}

<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\helpers\Url;
use app\components\helpers\Logger;
use app\components\traits\ModelTrait;

class Order extends Model
{
    const STATUS_NEW        = 0;
    const STATUS_CONFIRMED  = 1;
    const STATUS_DONE       = 2;

    public $order_id;
    public $total_price;
    public $customer_id;
    public $status;
    public $delivery_id;
    public $city;
    public $stock;
    public $create_time;
    public $update_time;

    public $products;
    public $quantity;

    public $email;
    public $phone;
    public $customer_name;


    use ModelTrait;

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
                ['customer_name', 'phone', 'city', 'stock'], 'string', 'min' => 3, 'max' => 100,
                'tooShort' => 'Длинна не менее 3-х символов',
                'tooLong' => 'Длинна не более 100 символов'
            ],

        ];
    }

    protected function get($key)
    {

    }

    protected function create()
    {
        $this->scenario = 'create';
        $this->attributes = Yii::$app->request->post();
        $this->create_time = $this->update_time = time();
        $this->total_price = 0;

        if(!$this->validate()){
            $this->grest->backData['errors'] = $this->errors;
            return $this->grest->setCode(400, 'N::Проверьте правильность заполнения полей.');
        }

//        $transaction = $this->db->beginTransaction();
        try{
            if(!User::findByEmail($this->email)){
                $user = new User();
                $user->email    = $this->email;
                $user->username = $this->customer_name;
                $user->phone    = $this->phone;
                $user->save();
            }
            echo '<pre>'; var_dump($this->db->getLastInsertID()); die;

            $this->db->createCommand()->insert('bs_order', [
                'customer_id'   => Yii::$app->user->identity->getId() ?? 0,
                'status'        => Order::STATUS_NEW,
                'delivery_id'   => $this->delivery_id,
                'city'          => $this->city,
                'stock'         => $this->stock,
                'create_time'   => $this->create_time,
                'update_time'   => $this->update_time,
            ])->execute();

            $this->order_id =  $this->db->getLastInsertID();

            foreach ($this->products as $key => $product_id){
                $product = Product::getProductById($product_id);
                $this->total_price += ($product['price']*$this->quantity[$key]);

                $this->db->createCommand()->insert('bs_order_product', [
                    'order_id'    => $this->order_id,
                    'product_id'  => $product_id,
                    'quantity'    => $this->quantity[$key],
                    'price'       => $product['price'],
                ])->execute();
            }

            $this->db->createCommand()->update('bs_order',
                ['total_price'   => $this->total_price],
                'order_id = ' . $this->order_id)->execute();

            $transaction->commit();
            return $this->grest->setCode(302, 'S::Ваш заказ успешно оформлен.', Url::previous());
        }catch (Exception $e){
            $transaction->rollBack();
            Logger::logException($e, 'Ошибка при обработке заказа');
            return $this->grest->setCode(499, 'Ошибка при обработке заказа');
        }

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
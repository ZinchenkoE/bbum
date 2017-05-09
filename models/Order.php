<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\components\traits\ModelTrait;
use yii\db\Exception;

class Order extends Model
{
    const STATUS_NEW        = 0;
    const STATUS_CONFIRMED  = 1;
    const STATUS_DONE       = 2;

    public $order_id;
    public $products;
    public $total_price;
    public $quantity;
    public $customer_id;
    public $customer_name;
    public $phone;
    public $status;
    public $delivery_id;
    public $city;
    public $stock;
    public $create_time;
    public $update_time;

    use ModelTrait;

    public function scenarios()
    {
        return [
            'create' => ['product_id', 'quantity', 'customer_id', 'customer_name', 'phone', 'status', 'delivery_id', 'city', 'stock', 'create_time', 'update_time']
        ];
    }

    public function rules()
    {
        return [
            [
                ['product_id', 'quantity', 'customer_id', 'status', 'delivery_id', 'city', 'stock'], 'integer'
            ],
            [
                ['customer_name', 'phone'], 'string'
            ],
            [['invoice_id','user_id','work_shift_id','domain_id'], 'required', 'on'=>'create' ],

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

//        echo '<pre>'; var_dump($this->attributes); die;

        if(!$this->validate()){
            return $this->grest->setCode(400, 'N::Проверьте правильность заполнения полей.');
        }


        $transaction = $this->db->transaction;
        try{
            $this->db->createCommand()->insert('bs_order', [
                'customer_id'   => Yii::$app->user->identity->getId(),
                'customer_name' => $this->customer_name,
                'phone'         => $this->phone,
                'status'        => Order::STATUS_NEW,
                'delivery_id'   => $this->delivery_id,
                'city'          => $this->city,
                'stock'         => $this->stock,
                'create_time'   => $this->create_time,
                'update_time'   => $this->update_time,
            ])->execute();

            foreach ($this->products as $product){
                $this->db->createCommand()->insert('bs_order', [
                    'product_id'    => $product['product_id'],
                    'price'         => $product['price'],
                    'quantity'      => $product['quantity'],
                ])->execute();
            }
            $transaction->commit();
            return $this->grest->setCode(200, 'S::Ваш заказ успешно оформлен.');
        }catch (Exception $e){
            $transaction->rollBack();
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
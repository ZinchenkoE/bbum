<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\Pagination;
use yii\db\Exception;
use yii\helpers\Url;
use app\components\helpers\Logger;
use app\components\traits\ModelTrait;

class Order extends Model
{
    const PAGE_LIMIT = 50;

    const STATUS_NEW        = 0; // новый
    const STATUS_CONFIRMED  = 1; // принятый
    const STATUS_DONE       = 2; // завершенный

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
                ['customer_name', 'phone', 'city', 'stock'], 'string', 'max' => 100,
                'tooLong' => 'Длинна не более 100 символов'
            ],

        ];
    }

    protected function get()
    {
        $order_id = Yii::$app->request->get('key');
        if ($order_id) {
            $order = $this->db->createCommand("
                SELECT 
                  o.order_id, 
                  cstm.customer_name, 
                  cstm.email, 
                  cstm.phone, 
                  ct.city_name, 
                  o.stock, 
                  o.total_price, 
                  o.status
                FROM bs_order o
                JOIN bs_customer cstm ON o.customer_id = cstm.customer_id
                JOIN bs_city ct ON o.city = ct.city_id
                JOIN bs_order_product op ON o.order_id = op.order_id
                WHERE o.order_id = {$order_id}
                ")->queryOne();
            $order_products = $this->db->createCommand("
                SELECT op.*, p.title_ru, p.price
                FROM bs_order_product op
                JOIN bs_product p ON op.product_id = p.product_id
                WHERE op.order_id = {$order_id}
                ")->queryAll();
            $this->grest->data['order']           = $order;
            $this->grest->data['order_products']  = $order_products;
            $this->grest->render                  = 'order';
        } else {
            $search = (string)Yii::$app->request->get('search');
            $page   = (int)   Yii::$app->request->get('page', 1);
            $search_part_query = $search ? " AND cstm.customer_name LIKE '%{$search}%' " : ' ';
            $offset = 'OFFSET '. ($page-1)*self::PAGE_LIMIT;
            $count  = $this->db->createCommand("SELECT COUNT(*) FROM bs_order " . $search_part_query )->queryScalar();
            $orders = $this->db->createCommand("
                SELECT 
                  o.order_id, 
                  cstm.customer_name, 
                  cstm.email, 
                  cstm.phone, 
                  ct.city_name, 
                  o.stock, 
                  o.total_price, 
                  o.status
                FROM bs_order o
                JOIN bs_customer cstm ON o.customer_id = cstm.customer_id
                JOIN bs_city ct ON o.city = ct.city_id
                WHERE 1 {$search_part_query}
                ORDER BY o.order_id DESC LIMIT " . self::PAGE_LIMIT. " {$offset}
                ")->queryAll();

            $pages = new Pagination([ 'totalCount' => $count, 'pageSize' => self::PAGE_LIMIT ]);
            $this->grest->data['orders'] = $orders;
            $this->grest->data['pages'] = $pages;
            $this->grest->render = 'index';
        }

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
        $transaction = $this->db->beginTransaction();
        try{
            $user_in_db = Customer::findByEmail($this->email);
            if(
                $user_in_db &&
                $user_in_db->customer_name === $this->customer_name &&
                $user_in_db->phone === $this->phone
            ){
                $this->customer_id = $user_in_db->customer_id;
            }else{
                $user = new Customer();
                $user->email         = $this->email;
                $user->customer_name = $this->customer_name;
                $user->phone         = $this->phone;
                $user->save();
                $this->customer_id   =  $this->db->getLastInsertID();
            }

            $this->db->createCommand()->insert('bs_order', [
                'customer_id'   => $this->customer_id,
                'status'        => Order::STATUS_NEW,
                'delivery_id'   => $this->delivery_id,
                'city'          => $this->city,
                'stock'         => $this->stock,
                'create_time'   => $this->create_time,
                'update_time'   => $this->update_time,
            ])->execute();

            $this->order_id = $this->db->getLastInsertID();

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
//            $this->sendMail();

            return $this->grest->setCode(302, 'S::Ваш заказ успешно оформлен.', Url::previous());
        }catch (Exception $e){
            $transaction->rollBack();
            Logger::logException($e, 'Ошибка при обработке заказа');
            return $this->grest->setCode(499, 'Ошибка при обработке заказа');
        }

    }

    protected function put(){
        return $this->grest->setCode(302, '', '/admin');
    }

    protected function remove($key){}

    public static function getOrderById($id)
    {
        return Yii::$app->db->createCommand("SELECT * FROM bs_order WHERE order_id = {$id}")->queryOne();
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

//        Yii::$app->mailer->compose()
//            ->setFrom('sale@baby-bum.in.ua')
//            ->setTo('to@domain.com')
//            ->setSubject('Тема сообщения')
//            ->setTextBody('Текст сообщения')
//            ->setHtmlBody('<b>текст сообщения в формате HTML</b>')
//            ->send();
        }catch(Exception $e){
            Logger::logException($e, 'Ошибка при отправке письма');
        }
    }
}
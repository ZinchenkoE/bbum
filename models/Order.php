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
                ['customer_name', 'phone', 'city', 'stock'], 'string', 'max' => 100,
                'tooLong' => 'Длинна не более 100 символов'
            ],

        ];
    }

    protected function get()
    {
//        $search = (string)Yii::$app->request->get('search');
//        $page   = (int)   Yii::$app->request->get('page');
//        $offset = $page == 0 || $page == 1 ? '' : 'OFFSET '. ($page-1)*self::PAGE_LIMIT;
//        $search_part_query = $search ? " AND title_ru LIKE '%{$search}%' " : ' ';
//        $count  = $this->db->createCommand(
//            "SELECT COUNT(*) FROM bs_order " . $search_part_query )->queryScalar();
//        $products = $this->db->createCommand("
//                SELECT *
//                FROM bs_order o
//                LEFT JOIN bs_category c ON p.category = c.category_id
//                LEFT JOIN bs_parent_category pc ON c.parent_id = pc.parent_category_id
//                WHERE p.product_status != ".self::STATUS_DELETED." {$search_part_query}
//                ORDER BY p.product_id DESC LIMIT " . self::PAGE_LIMIT. " {$offset}
//                ")->queryAll();
//
//        $this->grest->data['products'] = $products;
//        $pages = new Pagination([ 'totalCount' => $count, 'pageSize' => self::PAGE_LIMIT ]);
//        $this->grest->data['orders'] = $pages;
//        $this->grest->render = 'admin/index';
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
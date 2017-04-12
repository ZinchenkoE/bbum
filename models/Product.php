<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\Pagination;
use app\components\helpers\File;
use app\components\traits\ModelTrait;


class Product extends Model
{
    const PAGE_LIMIT = 50;
    public $title_ru;
    public $title_uk;
    public $description_ru;
    public $description_uk;
    public $price;
    public $gender;
    public $category;
    public $status;
    public $producer;
    public $removeImg;

    use ModelTrait;

    public function rules()
    {
        return [
            [
                [
                    'title_ru', 'title_uk', 'description_ru', 'description_uk', 'price', 'gender',
                    'category', 'status', 'producer'
                ], 'filter',
                'filter' => 'trim',
            ],
            [
                ['title_ru', 'title_uk'], 'string', 'min' => 3, 'max' => 50,
                'tooShort' => 'Длина не менее 3-х символов',
                'tooLong' => 'Длина не более 50 символов',
            ],
            [ ['title_ru', 'title_uk', 'description_ru'], 'required', 'message' => 'Поле не может быть пустым' ],
            [ 'removeImg', 'each', 'rule' => ['string'] ]
        ];
    }

    /**
     * @param $key
     */
    protected function get($key)
    {
        $this->grest->data['categories'] = Category::getCategories();
        if ($key == 'new') {
            $this->grest->data['action']     = 'create';
            $this->grest->render             = 'product/action-product';
        } elseif ($key) {
            $product = Product::getProduct($key);
            if (!$product) {
                $this->grest->setCode(302, 'Продукт не найден', '/admin/product');
            } else {
                $this->grest->data['product']    = $product;
                $this->grest->data['action']     = 'put';
                $this->grest->data['images']     = $this->db->createCommand("SELECT image_id, src FROM bs_product_img WHERE product_id = $key ")->queryAll();
                $this->grest->render             = 'product/action-product';
            }

        } else {
            $search = (string)Yii::$app->request->get('search');
			$page   = (int)   Yii::$app->request->get('page');

			$offset = $page == 0 || $page == 1? '' : 'OFFSET '. ($page-1)*self::PAGE_LIMIT;

            $search_part_query = $search ? " WHERE title_ru LIKE '%$search%' " : ' ';
//            $search_part_query = 'WHERE category = 0';

			$count = $this->db->createCommand("SELECT COUNT(*) FROM bs_product $search_part_query")->queryScalar();

            $products = $this->db->createCommand("
                SELECT * FROM bs_product p
                LEFT JOIN bs_category c ON p.category = c.category_id
                LEFT JOIN bs_parent_category pc ON c.parent_id = pc.parent_category_id  
                 $search_part_query  ORDER BY p.product_id  LIMIT " . self::PAGE_LIMIT. " {$offset} 
                ")->queryAll();

			$this->grest->data['products'] = $products;
			$pages = new Pagination([ 'totalCount' => $count, 'pageSize' => self::PAGE_LIMIT ]);
			$this->grest->data['pages'] = $pages;
            $this->grest->render = 'product/product-table';
        }
    }

    protected function create()
    {
        $this->attributes = Yii::$app->request->post();
        if ($this->validate()) {
            $key = $this->db->createCommand("SELECT AUTO_INCREMENT FROM information_schema.tables WHERE TABLE_NAME = 'bs_product';")->queryScalar();
            for ($i = 0; $i < 6; $i++){
                $file = File::loadFile('images_' . $i);
                if($file){
                    $src = $file ? $file->save('', $key . "_" . $i, 'imgs_')->getPath() : null;
                    $this->db->createCommand('bs_product_img', [
                        'product_id'    => $key,
                        'image_id'      => $i,
                        'src'           => $src,
                    ])->execute();
                }
            }
            $this->db->createCommand()->insert('bs_product', [
                    'title_ru'        => $this->title_ru,
                    'title_uk'        => $this->title_uk,
                    'description_ru'  => $this->description_ru,
                    'description_uk'  => $this->description_uk,
                    'price'           => $this->price,
                    'gender'          => $this->gender,
                    'category'        => $this->category,
                    'status'          => $this->status,
                    'producer'        => $this->producer,
                ])->execute();
            $this->grest->setCode(302, 'Новый продукт успешно добавлен', '/admin/product');
        } else {
            $this->grest->backData['error'] = $this->getErrors();
            $this->grest->setCode(400, 'Данные не могут быть добавлены');
        }
    }

    protected function put($key)
    {
        $this->attributes = Yii::$app->request->post();
        if ($this->validate()) {
            $this->db->createCommand()->update('bs_product', [
                'title_ru'       => $this->title_ru,
                'title_uk'       => $this->title_uk,
                'description_ru' => $this->description_ru,
                'description_uk' => $this->description_uk,
                'price'          => $this->price,
                'gender'         => $this->gender,
                'category'       => $this->category,
                'status'         => $this->status,
                'producer'       => $this->producer
            ], 'product_id = :id' )->bindValue(':id', $key)->execute();
            $this->grest->setCode(302, 'Данные успешно обновлены', '/admin/product');
        } else {
            $this->grest->backData['error'] = $this->getErrors();
            $this->grest->setCode(400, 'Данные не могут быть обновлены');
        }
    }

    protected function remove($key)
    {
        $r1 = $this->db->createCommand("DELETE FROM bs_product WHERE product_id = :id")->bindValue(':id', $key)->execute();
        $this->db->createCommand("DELETE FROM bs_product_img WHERE product_id = :id")->bindValue(':id', $key)->execute();
        if ($r1) {
            $this->grest->setCode(302, 'Продукт успешно удален', '/admin/product');
        } else {
            $this->grest->setCode(400, 'Продукт не может быть удален');
        }
    }

    protected function downloadImages(){
        $p = $this->db->createCommand("SELECT product_id, origin_images FROM bs_product WHERE images IS NULL")->queryAll();
        foreach ($p as $k) {
            $imgs = json_decode($k['origin_images']);
            $newImgs = [];
            $id = $k['product_id'];
            foreach ($imgs as $i => $src) {
                $f = file_get_contents($src);
                file_put_contents("res/imgs/{$id}_{$i}.jpg", $f);
                array_push($newImgs, "res/imgs/{$id}_{$i}.jpg");
            }
            $newImgsJSON = json_encode($newImgs);
            $this->db->createCommand()->update('bs_product', [
                'images' => $newImgsJSON
            ], 'product_id = :id')->bindValue(':id', $id)->execute();
            echo 'success!!!!!!!!!!!!!!!!!!';
        }
    }

    static function updateGroupProduct(){
        $products = Yii::$app->db->createCommand("SELECT * FROM bs_product WHERE price = 0")->queryAll();
        foreach ($products as $p) {
            Yii::$app->db->createCommand()->update('bs_product', [
                'price' => $p['origin_price'] + 20
            ], 'product_id = :id')->bindValue(':id', $p['product_id'])->execute();
        }
        echo 'success!!!!!!!!!!!!!!!!!!'; die;
    }

    public static function getProduct($id)
    {
        return Yii::$app->db->createCommand("SELECT * FROM bs_product WHERE product_id = :id")->bindValue(':id', $id)->queryOne();
    }

    protected function setCategoryPost(){ // Назначение категории к товару с таблицы
        $key =  Yii::$app->request->get('key');
        $category =  Yii::$app->request->post('category');
        $this->db->createCommand()->update('bs_product', ['category' => $category], 'product_id = :id')->bindValue(':id', $key)->execute();
        $this->grest->setCode(200, 'Продукт успешно обновлен');
    }
}
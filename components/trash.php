<?php
use yii\web\BadRequestHttpException;
use Yii;

trait ModelTrait
{
    /** @var \yii\db\Connection */
    private $db;

    /** @var \app\components\Grest */
    private $grest;

    public function __construct()
    {
        $this->db = &Yii::$app->db;
        $this->grest = &Yii::$app->grest;
    }

    public function __destruct()
    {
        $this->db->close();
    }

    public static function initModel()
    {
        return new static();
    }

    public function run($key = null, $id = null)
    {
        if (!Yii::$app->request->isAjax && !Yii::$app->request->isGet){
            throw new BadRequestHttpException('Не верный тип запроса');
        }

        $callMethod = 'get';

        if (Yii::$app->request->isAjax){
            if (Yii::$app->request->isPost){
                if (Yii::$app->request->post('_rm')){
                    $callMethod = Yii::$app->request->post('_rm');
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
        if (Yii::$app->request->post('_prm')) {
            $callMethod = (Yii::$app->request->post('_prm'))."Post";
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
        $this->grest->setCode(499, null);
        $this->grest->backData['error'] = $errors;
    }

}



function downloadImages(){
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

class Category extends Model
{

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE     = 1;
    const STATUS_DELETED    = 2;

    public $category_title_ru;
    public $category_title_uk;
    public $parent_id;
    public $removeImg = [];

    use ModelTrait;

    public function rules()
    {
        return [
            [
                ['category_title_ru', 'category_title_uk'], 'string', 'min' => 3, 'max' => 50,
                'tooShort' => 'Длина не менее 3-х символов',
                'tooLong' => 'Длина не более 50 символов',
            ],
            [
                ['category_title_ru', 'category_title_uk', 'parent_id'], 'required',
                'message' => 'Поле не может быть пустым'
            ],
//            [
//                ['image'], 'file', 'maxSize' => 1048576, 'mimeTypes' => ['image/jpeg', 'image/png'],
//                'message'       => 'Ошибка загрузки файла',
//                'tooBig'        => 'Размер фото должен быть до 1Мб',
//                'wrongMimeType' => 'Неверный формат файла',
//            ],
            [
                'removeImg', 'each', 'rule' => ['string']
            ],
            [
                ['parent_id'], 'integer', 'message' => 'Неверный формат id родительской категории',
            ],
        ];
    }

    protected function get($key)
    {
        $this->grest->data['categories']        = Category::getCategories();
        $this->grest->data['parent_categories'] = Category::getParentCategories();
        if ($key == 'new') {
            $this->grest->data['action']   = 'create';
            $this->grest->render           = 'category/action-category';
        } elseif ($key) {
            $this->grest->data['action']   = 'put';
            $this->grest->data['category'] = $this->db->createCommand(
                "SELECT * FROM bs_category WHERE category_id = {$key} AND category_status != " . self::STATUS_DELETED)->queryOne();
            $this->grest->render           = 'category/action-category';
        } else {
            $this->grest->render           = 'category/category-table';
        }
    }

    protected function create()
    {
        $this->attributes = Yii::$app->request->post();
        if (!$this->validate()) {
            $this->grest->backData['error'] = $this->getErrors();
            return $this->grest->setCode(400, 'Данных не могут быть добавлены');
        }
        $key = $this->db->createCommand(
            "SELECT AUTO_INCREMENT FROM information_schema.tables WHERE TABLE_NAME = 'bs_category';")->queryScalar();
        $file = File::loadFile('image');
        if($file){
            $src = $file ? $file->save('', $key, 'category')->getPath() : null;
        }
        $v = [
            ':category_title_ru' => $this->category_title_ru,
            ':category_title_uk' => $this->category_title_uk,
            ':image'             => $src ?? null,
            ':parent_id'         => $this->parent_id,
        ];
        $this->db->createCommand("
            INSERT INTO bs_category (category_title_ru, category_title_uk, image, parent_id) 
            VALUES (:category_title_ru, :category_title_uk, :image, :parent_id)")->bindValues($v)->execute();
        return $this->grest->setCode(302, 'Новая категория успешно добавлена', '/admin/category');
    }

    protected function put($key)
    {
        $this->attributes = Yii::$app->request->post();
        if (!$this->validate()) {
            $this->grest->backData['error'] = $this->getErrors();
            return $this->grest->setCode(400, 'Данные не могут быть обновлены');
        }
        foreach ($this->removeImg as $i){
            if($i && file_exists($img = substr($i, 1))){
                unlink( $img );
                $src = '';
            }
        }

        $file = File::loadFile('image');
        if($file){
            $src = $file ? $file->save('', $key, 'category')->getPath() : null;
        }

        $v = [
            ':category_title_ru' => $this->category_title_ru,
            ':category_title_uk' => $this->category_title_uk,
            ':id'                => $key,
            ':parent_id'         => $this->parent_id,
        ];
        $this->db->createCommand("UPDATE bs_category SET 
            category_title_ru=:category_title_ru, 
            category_title_uk=:category_title_uk, 
            parent_id=:parent_id, 
            image=" . (isset($src) ? "'{$src}'" : 'image') . " 
            WHERE category_id=:id")->bindValues($v)->execute();
        $code = Yii::$app->request->post('need_redirect') == 1 ? 302 : 200;
        return $this->grest->setCode($code, 'Данные успешно обновлены', '/admin/category');
    }

    protected function remove($key)
    {
        $r = $this->db->createCommand(
            "UPDATE bs_category SET category_status = " . self::STATUS_DELETED . " WHERE category_id = {$key}")->execute();
        if ($r) {
            $this->grest->setCode(302, 'Категория успешно удалена', '/admin/category');
        } else {
            $this->grest->setCode(400, 'Категория не может быть удалена. ' . implode('; ', $this->getErrors('flash')));
        }
    }

    public static function getParentCategories()
    {
        return Yii::$app->db->createCommand("SELECT * FROM bs_parent_category")->queryAll();
    }

    public static function getCategories()
    {
        return Yii::$app->db->createCommand("
            SELECT * FROM bs_category c 
            LEFT JOIN bs_parent_category pc ON c.parent_id = pc.parent_category_id 
            WHERE c.category_status != " . self::STATUS_DELETED. " ORDER BY category_title_ru")->queryAll();
    }

    protected function changeStatusPost(){
        try{
            $category_id =  Yii::$app->request->get('key');
            $status =  Yii::$app->request->post('category_status');
            $this->db->createCommand()->update('bs_category',
                ['category_status' => $status], 'category_id = '.$category_id)->execute();
            $this->grest->setCode(200, 'Статус успешно обновлен');
        }catch (\Exception $e){
            $this->grest->setCode(400, 'Ошибка при изменении статуса');
            Logger::logException($e, 'Ошибка при изменении статуса категории');
        }
    }
}



class Product extends Model
{
    const PAGE_LIMIT = 50;

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE     = 1;
    const STATUS_DELETED    = 2;

    public $title_ru;
    public $title_uk;
    public $description_ru;
    public $description_uk;
    public $price;
    public $gender;
    public $category;
    public $product_status;
    public $producer;
    public $removeImg;

    use ModelTrait;

    public function rules()
    {
        return [
            [
                [
                    'title_ru', 'title_uk', 'description_ru', 'description_uk', 'price', 'gender',
                    'category', 'product_status', 'producer'
                ], 'filter',
                'filter' => 'trim',
            ],
            [
                ['title_ru', 'title_uk'], 'string', 'min' => 3, 'max' => 50,
                'tooShort' => 'Длина не менее 3-х символов',
                'tooLong' => 'Длина не более 50 символов',
            ],
            [ ['title_ru', 'title_uk', 'description_ru', 'description_uk', 'price'], 'required',
                'message' => 'Поле не может быть пустым' ],
            [ 'removeImg', 'each', 'rule' => ['string'] ]
        ];
    }

    /**
     * @param $key
     */
    protected function get($key)
    {
        $this->grest->data['categories']        = Category::getCategories();
        $this->grest->data['parent_categories'] = Category::getParentCategories();
        if ($key == 'new') {
            $this->grest->data['action'] = 'create';
            $this->grest->render         = 'product/action-product';
        } elseif ($key) {
            $product = Product::getProductById($key);
            if (!$product) {
                $this->grest->setCode(302, 'Продукт не найден', '/admin/product');
            } else {
                $this->grest->data['product']= $product;
                $this->grest->data['action'] = 'put';
                $this->grest->data['images'] = $this->db->createCommand(
                    "SELECT image_id, src FROM bs_product_img WHERE product_id = {$key}")->queryAll();
                $this->grest->render = 'product/action-product';
            }
        } else {
            $search = (string)Yii::$app->request->get('search');
            $page   = (int)   Yii::$app->request->get('page');
            $offset = $page == 0 || $page == 1 ? '' : 'OFFSET '. ($page-1)*self::PAGE_LIMIT;
            $search_part_query = $search ? " AND title_ru LIKE '%{$search}%' " : ' ';
            $count  = $this->db->createCommand(
                "SELECT COUNT(*) FROM bs_product WHERE status != ".self::STATUS_DELETED. $search_part_query )->queryScalar();
            $products = $this->db->createCommand("
                SELECT *
                FROM bs_product p
                LEFT JOIN bs_category c ON p.category_id = c.category_id
                LEFT JOIN bs_parent_category pc ON c.parent_id = pc.parent_category_id  
                WHERE p.status != ".self::STATUS_DELETED." {$search_part_query}  
                ORDER BY p.product_id DESC LIMIT " . self::PAGE_LIMIT. " {$offset} 
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
        if (!$this->validate()) {
            $this->grest->backData['error'] = $this->getErrors();
            return $this->grest->setCode(400, 'Данные не могут быть добавлены');
        }
        $key = $this->db->createCommand(
            "SELECT AUTO_INCREMENT FROM information_schema.tables WHERE TABLE_NAME = 'bs_product';")->queryScalar();
        for ($i = 0; $i < 6; $i++){
            $file = File::loadFile('images_' . $i);
            if($file){
                $src = $file ? $file->save('', $key . "_" . $i, 'imgs')->getPath() : null;
                $this->db->createCommand()->insert('bs_product_img', [
                    'product_id' => $key,
                    'image_id'   => $i,
                    'src'        => $src,
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
            'product_status'  => $this->product_status,
            'producer'        => $this->producer,
        ])->execute();
        return $this->grest->setCode(302, 'Новый продукт успешно добавлен', '/admin/product');
    }

    protected function put($key)
    {
        $this->attributes = Yii::$app->request->post();
        if (!$this->validate()) {
            $this->grest->backData['error'] = $this->getErrors();
            return $this->grest->setCode(400, 'Данные не могут быть обновлены');
        }
        $this->db->createCommand()->update('bs_product', [
            'title_ru'       => $this->title_ru,
            'title_uk'       => $this->title_uk,
            'description_ru' => $this->description_ru,
            'description_uk' => $this->description_uk,
            'price'          => $this->price,
            'gender'         => $this->gender,
            'category'       => $this->category,
            'product_status' => $this->product_status,
            'producer'       => $this->producer
        ], 'product_id = '.$key )->execute();
        return $this->grest->setCode(302, 'Данные успешно обновлены', '/admin/product');
    }

    protected function remove($key)
    {
        $result = $this->db->createCommand(
            "UPDATE bs_product SET status = ".self::STATUS_DELETED." WHERE product_id = {$key}")->execute();
        if ($result) {
            $this->grest->setCode(302, 'Продукт успешно удален', '/admin/product');
        } else {
            $this->grest->setCode(400, 'Продукт не может быть удален');
        }
    }

    public static function getProductById($id)
    {
        return Yii::$app->db->createCommand("SELECT * FROM bs_product WHERE id = {$id}")->queryOne();
    }

    protected function setCategoryPost(){ // Назначение категории к товару с таблицы
        $key =  Yii::$app->request->get('key');
        $category =  Yii::$app->request->post('category');
        $this->db->createCommand()->update('bs_product', ['category' => $category], 'product_id = '.$key)->execute();
        $this->grest->setCode(200, 'Продукт успешно обновлен');
    }

    protected function changeStatusPost(){
        $product_id =  Yii::$app->request->get('key');
        $status =  Yii::$app->request->post('product_status');
        $this->db->createCommand()->update('bs_product', ['product_status' => $status], 'product_id = '.$product_id)->execute();
        $this->grest->setCode(200, 'Статус успешно обновлен');
    }


}


class SiteCategory extends Model
{
    use ModelTrait;
    const PAGE_LIMIT = 50;

    protected function get($key, $id)
    {
//        $this->grest->data['categories'] = SiteCategory::getCategories();
//        $this->grest->data['parent_categories'] = $this->db->createCommand("SELECT * FROM bs_parent_category")->queryAll();

        $search        = (string)Yii::$app->request->get('search');
        $page          = (int)   Yii::$app->request->get('page');
        $tag           = (string)Yii::$app->request->get('tag');
        $categories    = (string)Yii::$app->request->get('categories');
        $gender_in_arr = [];

        (bool)Yii::$app->request->get('for_unisex') ? array_push($gender_in_arr, 0) : null;
        (bool)Yii::$app->request->get('for_boy')    ? array_push($gender_in_arr, 1) : null;
        (bool)Yii::$app->request->get('for_girl')   ? array_push($gender_in_arr, 2) : null;
        $gender_filter = $gender_in_arr ? ' AND p.gender IN (' . implode(',', $gender_in_arr) . ') ' : '';

        $price_from = (int) Yii::$app->request->get('price_from');
        $price_to   = (int) Yii::$app->request->get('price_to');

        $price_filter = $price_from && $price_to ? ' AND p.price >= ' . $price_from . ' AND p.price <= ' . $price_to : '';

        $offset = $page == 0 || $page == 1 ? 0 :  ($page-1)*self::PAGE_LIMIT;
//        $search_part_query = $search ? " WHERE title_ru LIKE '%$search%' " : ' ';
        $show_gender_filter = true;
        $where = 'WHERE 1';

        if ($id) { // Вывод товаров категории второго уровня
            $where = 'WHERE c.category_id = ' . (int)$id;
            $show_gender_filter = false;
        } elseif ($key) {  // Вывод товаров категории первого уровня
            $where = 'WHERE pc.parent_category_id = ' . (int)$key;
            $show_gender_filter = false;
        } elseif ($tag) {
            $where = "WHERE c.tag = '{$tag}'";
        } elseif ($categories) {
            $where = "WHERE p.category IN ({$categories})";
        }

        $prices = $this->db->createCommand("
                SELECT  
                  MIN(p.price) AS min_price, 
                  MAX(p.price) AS max_price
                FROM bs_product p
                LEFT JOIN bs_category c         ON p.category_id = c.category_id
                LEFT JOIN bs_parent_category pc ON c.parent_id = pc.parent_category_id $where")->queryOne();
        $where .= $gender_filter.$price_filter;

        $count = $this->db->createCommand("
                SELECT  COUNT(p.product_id)     FROM bs_product p
                LEFT JOIN bs_category c         ON p.category_id = c.category_id
                LEFT JOIN bs_parent_category pc ON c.parent_id = pc.parent_category_id $where")->queryScalar();

        $products = $this->db->createCommand("
                SELECT p.*, MIN(p_i.src) AS img_src, c.*  FROM bs_product p
                LEFT JOIN bs_category c         ON p.category_id = c.category_id
                LEFT JOIN bs_parent_category pc ON c.parent_id = pc.parent_category_id
                LEFT JOIN bs_product_img p_i    ON p.product_id  = p_i.product_id 
                $where AND p.product_status = ".Product::STATUS_ACTIVE."
                GROUP BY  p.product_id
                ORDER BY p.product_id  LIMIT :limit OFFSET :offset
                ")->bindValues([
            ':limit'  => self::PAGE_LIMIT,
            ':offset' => $offset
        ])->queryAll();
        $pages = new Pagination([ 'totalCount' => $count, 'pageSize' => self::PAGE_LIMIT ]);
        $this->grest->data = [
            'pages'              => $pages,
            'products'           => $products,
            'min_price'          => $prices['min_price'],
            'max_price'          => $prices['max_price'],
            'show_gender_filter' => $show_gender_filter,
        ];
        $this->grest->render = 'category';
    }

    public static function getCategoriesForMenu()
    {
        return Yii::$app->db->createCommand("
            SELECT pc.*,  c.*, c.parent_id, c.category_id, COUNT(p.product_id) AS count_product  
            FROM bs_category c 
            LEFT JOIN bs_parent_category pc ON c.parent_id = pc.parent_category_id 
            INNER JOIN bs_product p ON p.category_id = c.category_id  
            WHERE c.category_status = ".Category::STATUS_ACTIVE."
            GROUP BY c.category_id
            ORDER BY c.category_title_ru")->queryAll();
    }
}


class SiteProduct extends Model
{
    use ModelTrait;

    protected function get($key)
    {
        $this->grest->data['categories'] = Category::getCategories();
        if ($key) {
            $product = $this->db->createCommand("
                SELECT p.*, c.*  FROM bs_product p
                LEFT JOIN bs_category c ON p.category_id = c.category_id
                WHERE p.product_id = {$key} AND p.product_status = ".Product::STATUS_ACTIVE )->queryOne();
            if($product){
                $this->grest->data['product'] = $product;
                $this->grest->data['images'] = $this->db->createCommand("
                    SELECT src  FROM bs_product_img  WHERE product_id = {$key}")->queryAll();
                $this->grest->render = 'product';
            } else {
                $this->grest->setCode(301, 'Продукт не найден', '/category');
            }

        } else {
            $this->grest->setCode(301, null, '/');
        }
    }

}
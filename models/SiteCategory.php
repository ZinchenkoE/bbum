<?php
namespace app\models;

use Yii;
use app\components\traits\ModelTrait;
use yii\base\Model;
use yii\data\Pagination;

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
                LEFT JOIN bs_category c         ON p.category = c.category_id
                LEFT JOIN bs_parent_category pc ON c.parent_id = pc.parent_category_id $where")->queryOne();
        $where .= $gender_filter.$price_filter;

        $count = $this->db->createCommand("
                SELECT  COUNT(p.product_id)     FROM bs_product p
                LEFT JOIN bs_category c         ON p.category = c.category_id
                LEFT JOIN bs_parent_category pc ON c.parent_id = pc.parent_category_id $where")->queryScalar();

        $products = $this->db->createCommand("
                SELECT p.*, MIN(p_i.src) AS img_src, c.*  FROM bs_product p
                LEFT JOIN bs_category c         ON p.category = c.category_id
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
            INNER JOIN bs_product p ON p.category = c.category_id  
            WHERE c.category_status = ".Category::STATUS_ACTIVE."
            GROUP BY c.category_id
            ORDER BY c.category_title_ru")->queryAll();
    }
}

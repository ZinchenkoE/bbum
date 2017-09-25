<?php
use yii\base\Model;
use yii\data\Pagination;
use app\components\traits\ModelTrait;

class SiteProduct extends Model
{
    use ModelTrait;

    protected function get($key)
    {
        $this->grest->data['categories'] = Category::getCategories();
        if ($key) {
            $product = $this->db->createCommand("
                SELECT p.*, c.*  FROM bs_product p
                LEFT JOIN bs_category c ON p.category = c.category_id
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
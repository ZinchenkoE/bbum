<?php
namespace app\models;

use app\components\helpers\File;
use app\components\interfaces\ModelInterface;
use app\components\traits\ModelTrait;
use yii\base\Model;
use yii\data\Pagination;

class SiteProduct extends Model implements ModelInterface
{
    use ModelTrait;

    protected function get($key)
    {
        $this->grest->data['categories'] = Category::getCategories();
        if ($key) {
            $product = $this->db->createCommand("
                SELECT p.*, c.*  FROM bs_product p
                LEFT JOIN bs_category c      ON p.category = c.category_id
                WHERE p.product_id = :id")->bindValues([':id' => $key])->queryOne();
            if($product){
                $this->grest->data['product'] = $product;
                $this->grest->data['images'] = $this->db->createCommand("
                    SELECT src  FROM bs_product_img  WHERE product_id = :id")->bindValues([':id' => $key])->queryAll();
                $this->grest->render = 'product';
            } else {
                $this->grest->setCode(301, 'Продукт не найден', '/category');
            }

        } else {
            $this->grest->setCode(301, null, '/');
        }
    }

}
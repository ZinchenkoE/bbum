<?php
namespace app\models;

use Yii;
use app\components\traits\ModelTrait;

class SiteIndex
{
    use ModelTrait;

    protected function get()
    {
        $this->grest->data['product_recommend'] = Yii::$app->db->createCommand("
                SELECT p.*, c.*, MIN(p_i.src) AS img_src  FROM bs_product_recommend pr
                LEFT JOIN bs_product p       ON pr.product_recommend_id = p.product_id
                LEFT JOIN bs_category c      ON p.category = c.category_id
                LEFT JOIN bs_product_img p_i ON p.product_id  = p_i.product_id 
                GROUP BY  p.product_id
                ")->queryAll();
        $this->grest->render = '/site/index';
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

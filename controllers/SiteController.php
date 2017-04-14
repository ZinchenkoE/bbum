<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\helpers\Logginer;
use app\models\SiteProduct;
use app\models\SiteCategory;

class SiteController extends Controller
{
    public $layout = 'site';

    public function actionIndex()
    {
        Yii::$app->grest->data['product_recommend'] = Yii::$app->db->createCommand("
                SELECT p.*, c.*, MIN(p_i.src) AS img_src  FROM bs_product_recommend pr
                LEFT JOIN bs_product p       ON pr.product_recommend_id = p.product_id
                LEFT JOIN bs_category c      ON p.category = c.category_id
                LEFT JOIN bs_product_img p_i ON p.product_id  = p_i.product_id 
                GROUP BY  p.product_id
                ")->queryAll();

        return Yii::$app->grest->render();
    }

    public function actionInfo()
    {
        Yii::$app->grest->render = 'info';
        return Yii::$app->grest->render();
    }

    public function actionContacts()
    {
        Yii::$app->grest->render = 'contacts';
        return Yii::$app->grest->render();
    }

    public function actionCategory($key = null, $id = null)
    {
        SiteCategory::initModel()->run($key, $id);
        return Yii::$app->grest->render();
    }

    public function actionProduct($key = null, $id = null)
    {
        SiteProduct::initModel()->run($key, $id);
        return Yii::$app->grest->render();
    }

    public function actionError()
    {
        echo 'Произошла ошибка сервера!';
//        return Yii::$app->grest->render();
    }
}

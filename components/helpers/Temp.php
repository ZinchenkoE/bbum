<?php
namespace app\models;

use Yii;

class Temp
{
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

}
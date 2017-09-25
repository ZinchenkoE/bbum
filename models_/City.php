<?php

use yii\db\ActiveRecord;

/**
 * Class User
 * @package app\models\user
 * @property string  $city_id
 * @property string  $city_name
 */

class City extends ActiveRecord
{
    public static function tableName() { return 'bs_city'; }

    public static function findById($city_id)
    {
        return static::findOne(['city_id' => $city_id]);
    }

    public function getId()
    {
        return $this->city_id;
    }

}

<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Product
 * @package app\models
 * @property integer  $id
 * @property string   $title_ru
 * @property string   $title_uk
 * @property string   $description_ru
 * @property string   $description_uk
 * @property string   $images
 * @property integer  $price
 * @property integer  $gender
 * @property integer  $category
 * @property integer  $update_time
 * @property integer  $status
 * @property integer  $producer
 * @property integer  $recommended
 *
 */

class Product extends ActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE     = 1;
    const STATUS_DELETED    = 2;


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

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getImgs()
    {
        return json_decode($this->images);
    }

    public function getTitle(): string
    {
        return Yii::$app->request->get('lng') === 'uk' ? $this->title_uk : $this->title_ru;
    }

    public function getDescription(): string
    {
        return Yii::$app->request->get('lng') === 'uk' ? $this->description_uk : $this->description_ru;
    }


}
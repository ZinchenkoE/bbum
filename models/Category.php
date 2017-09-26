<?php
namespace app\models;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class Category
 * @package app\models
 * @property integer $id
 * @property string  $title_ru
 * @property string  $title_uk
 * @property string  $image
 * @property integer $parent_id
 * @property string  $tag
 * @property integer $status
 */

class Category extends ActiveRecord
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE     = 1;
    const STATUS_DELETED    = 2;

    public function rules()
    {
        return [
            [
                ['title_ru', 'title_uk'], 'string', 'min' => 3, 'max' => 50,
                'tooShort' => 'Длина не менее 3-х символов',
                'tooLong' => 'Длина не более 50 символов',
            ],
            [
                ['title_ru', 'title_uk', 'parent_id'], 'required',
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

    public static function getRootCategory() {
        return static::findAll(['parent_id' => 0]);
    }

    public function getChildren()
    {
        return Category::find()
            ->innerJoin('bs_product', '`bs_product`.`category_id` = `bs_category`.`id`')
            ->where(['parent_id' => $this->id])
            ->all();
    }

    public function getTitle()
    {
        return Yii::$app->request->get('lng') === 'uk' ? $this->title_uk : $this->title_ru;
    }

}

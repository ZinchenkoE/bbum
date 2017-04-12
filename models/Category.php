<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\components\helpers\File;
use app\components\traits\ModelTrait;

class Category extends Model
{

    const STATUS_ACTIVE     = 1;
    const STATUS_DELETED    = 0;
    const STATUS_NOT_ACTIVE = 2;

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
        $this->grest->data['categories'] = Category::getCategories();
        $this->grest->data['parent_categories'] = $this->db->createCommand("SELECT * FROM bs_parent_category")->queryAll();

        if ($key == 'new') {
            $this->grest->data['action']   = 'create';
            $this->grest->render           = 'category/action-category';
        } elseif ($key) {
            $this->grest->data['action']   = 'put';
            $this->grest->data['category'] = $this->db->createCommand(
                "SELECT * FROM bs_category WHERE category_id = {$key} AND status != " . self::STATUS_DELETED)->queryOne();
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
        $this->grest->setCode(302, 'Новая категория успешно добавлена', '/admin/category');
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
        $this->grest->setCode($code, 'Данные успешно обновлены', '/admin/category');
    }

    protected function remove($key) 
    {
        $r = $this->db->createCommand("UPDATE bs_category SET status = 0 WHERE category_id = {$key}")->execute();
        if ($r) {
            $this->grest->setCode(302, 'Категория успешно удалена', '/admin/category');
        } else {
            $this->grest->setCode(400, 'Категория не может быть удалена. ' . implode('; ', $this->getErrors('flash')));
        }
    }

    public static function getCategories()
    {
        return Yii::$app->db->createCommand("
            SELECT * FROM bs_category c 
            LEFT JOIN bs_parent_category pc ON c.parent_id = pc.parent_category_id 
            WHERE c.status <> " . self::STATUS_DELETED. " ORDER BY category_title_ru")->queryAll();
    }
}

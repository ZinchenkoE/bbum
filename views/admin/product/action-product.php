<?php
$isPut = $data['action'] == 'put';
$product    = $isPut ? $data['product'] : null;
?>
<style>
    .fileField{ display: inline-block; }
</style>
<div class="formPage">
    <form class="formLogin" action="/admin/product/<?= $isPut ? $product['product_id'] : 'new' ?>" method="<?= $data['action'] ?>">
        <h1 class="pageTitle"><?= $isPut ? 'Редактирование' : 'Добавление' ?> товара</h1>
        <div class="leftCol">
            <h2>Данные продукта № <?= $product['product_id']?></h2>
            <div class="textField inputBox">
                <input placeholder="Название RU" id="title_ru" name="title_ru" type="text" value="<?= $isPut ? $product['title_ru'] : '' ?>" pattern="text" required>
            </div>
            <div class="textField inputBox">
                <input placeholder="Название UA" id="title_uk" name="title_uk" type="text" value="<?= $isPut ? $product['title_uk'] : '' ?>" pattern="text" required>
            </div>
            <div class="textField inputBox">
                <textarea placeholder="Описание RU" id="description_ru" name="description_ru" pattern="text" required><?= $isPut ? $product['description_ru'] : '' ?></textarea>
            </div>
            <div class="textField inputBox">
                <textarea placeholder="Описание UA" id="description_uk" name="description_uk" pattern="text" required><?= $isPut ? $product['description_uk'] : '' ?></textarea>
            </div>
            <div class="selectField inputBox">
                <label class="title">Категория</label>
                <select name="category">
                    <option value="0" <?= $product['category'] == 0 ? 'selected' : '' ?>> --- категория не выбрана --- </option>
                  <?php if (!empty($data['categories'])): ?>
                      <?php foreach ($data['categories'] as $category): ?>
                            <option value="<?= $category['category_id'] ?>" <?= $product['category'] == $category['category_id'] ? 'selected' : '' ?>
                            ><?= $category['parent_category_title_ru'] . ' > ' . $category['category_title_ru'] ?></option>
                      <?php endforeach; ?>
                  <?php endif; ?>
                </select>
            </div>
            <div class="selectField inputBox">
                <label class="title">Пол</label>
                <select name="gender">
                    <option value="0" <?= $product['gender'] == 0 ? 'selected' : '' ?>>Унисекс</option>
                    <option value="1" <?= $product['gender'] == 1 ? 'selected' : '' ?>>Для мальчика</option>
                    <option value="2" <?= $product['gender'] == 2 ? 'selected' : '' ?>>Для девочки</option>
                </select>
            </div>
            <div class="selectField inputBox">
                <label class="title">Производитель</label>
                <select name="producer">
                    <option value="0" <?= $product['gender']   == 0 ? 'selected' : '' ?>>Не указан</option>
                    <option value="1" <?= $product['producer'] == 1 ? 'selected' : '' ?>>Солнышко (Комсомольск, Украина) </option>
                </select>
            </div>
        </div>
        <div class="rightCol">
            <h2>Фотографии</h2>
<!--            --><?php //if (!empty($product['images'])): ?>
<!--                --><?php //$images = json_decode($product['images']);  ?>
<!--                --><?php //foreach ($images as $src): ?>
<!--                    <div class="fileField">-->
<!--                        <label>-->
<!--                            <input name="images[]" type="file" data-crop-img="1200" data-img-src="/--><?//= $src ?><!--">-->
<!--                        </label>-->
<!--                    </div>-->
<!--                --><?php //endforeach; ?>
<!--            --><?php //endif; ?>
            <?php for ($i = 0; $i < 6; $i++): ?>
                <div class="fileField">
                    <label>
                        <input name="images_<?= $i ?>" type="file" data-crop-img="1200"
                               data-img-src="<?= isset($data['images'][$i]) ? '/' . $data['images'][$i]['src'] : '' ?>">
                    </label>
                </div>
            <?php endfor; ?>
            
            <div class="switch withLabel">
                <label>
                    <span class="title">Статус</span>
                    <input name="status" type="checkbox" <?= $product['status'] ? 'checked' : '' ?>>
                    <span class="lever"></span>
                </label>
            </div>
            <div class="textField inputBox">
                <input placeholder="Цена" id="price" name="price" type="text" value="<?= $isPut ? $product['price'] : '' ?>" pattern="integer" required>
            </div>
        </div>
        <button class="fixedSubmit" type="submit"><?= $isPut ? 'Редактировать' : 'Добавить' ?></button>
    </form>
</div>
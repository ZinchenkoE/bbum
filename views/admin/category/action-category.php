<?php
$if = $data['action'] == 'put';
$category = $if ? $data['category'] : null;
?>
<div class="formPage row">
    <form action="/admin/category/<?= $if ? $category['category_id'] : 'new' ?>" method="<?= $data['action'] ?>">
        <h1 class="pageTitle"><?= $if ? 'Редактирование' : 'Добавление' ?> категории</h1>
        <div class="col m6">
            <h2>Данные</h2>
            <div class="textField inputBox">
                <input placeholder="Название RU" id="category_ru" name="category_title_ru" type="text" value="<?= $if ? $category['category_title_ru'] : '' ?>" pattern="text" required>
            </div>
            <div class="textField inputBox">
                <input placeholder="Название UA" id="category_ua" name="category_title_ua" type="text" value="<?= $if ? $category['category_title_ua'] : '' ?>" pattern="text" required>
            </div>
            <div class="selectField inputBox">
                <label class="title">Родительская категория</label>
                <select name="parent_id">
                    <option value="0"> --- нет родителя --- </option>
                    <?php if (!empty($data['parent_categories'])): ?>
                        <?php foreach ($data['parent_categories'] as $parent): ?>
                            <option value="<?= $parent['parent_category_id'] ?>"
                                <?= $category['parent_id'] && $category['parent_id'] == $parent['parent_category_id'] ? 'selected' : '' ?>>
                                <?= $parent['parent_category_title_ru'] ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        <div class="col m6">
<!--            <h2>Фотография</h2>-->
<!--            <div class="fileField">-->
<!--                <label><input name="image" type="file" data-crop-img="500" data-img-src="--><?//= $if && $category['image'] ? '/'.$category['image'] : '' ?><!--"></label>-->
<!--            </div>-->
        </div>
        <button class="fixedSubmit" type="submit"><?= $if ? 'Редактировать' : 'Добавить' ?></button>
    </form>
</div>
<?php
/** @var array $data  */

$product = $data['product'];
?>
<style>
    .fileField{ display: inline-block; }
</style>
<div class="formPage" data-objs="ActionProduct" id="ActionProduct">
    <form action="/admin/product" method="post">
        <h1 class="pageTitle"><?= $product->id ? 'Редактирование товара №'. $product->id : 'Добавление товара' ?></h1>
        <div class="leftCol">
            <div class="textField inputBox">
                <input placeholder="Название RU" name="title_ru" value="<?= $product->title ?>"
                       pattern="text" required>
            </div>
            <div class="textField inputBox">
                <input placeholder="Название UA" name="title_uk" value="<?= $product->title ?>"
                       pattern="text" required>
            </div>
            <div class="textField inputBox">
                <textarea placeholder="Описание RU" name="description_ru" pattern="text"
                          required><?= $product->description_ru ?></textarea>
            </div>
            <div class="textField inputBox">
                <textarea placeholder="Описание UA" name="description_uk" pattern="text"
                          required><?= $product->description_uk ?></textarea>
            </div>
			<div class="textField inputBox">
				<input placeholder="Цена" id="price" name="price" value="<?= $product->price ?>" pattern="integer" required>
			</div>
			<div class="switch withLabel">
				<label>
					<span class="title">Статус</span>
					<input name="status" type="checkbox" <?= $product->status ? 'checked' : '' ?>>
					<span class="lever"></span>
				</label>
			</div>
        </div>
        <div class="rightCol">
<!--			<div class="selectField inputBox">-->
<!--				<label class="title">Родительская категория</label>-->
<!--				<select id="parentCategory">-->
<!--					<option value="0" selected> --- категория не выбрана --- </option>-->
<!--                    --><?php //foreach ($data['parent_categories'] ?? [] as $parent_category): ?>
<!--						<option value="--><?//= $parent_category['parent_category_id'] ?><!--">-->
<!--							--><?//= $parent_category['parent_category_title_ru'] ?>
<!--						</option>-->
<!--                    --><?php //endforeach; ?>
<!--				</select>-->
<!--			</div>-->
<!--			<div class="selectField inputBox" id="childCategory">-->
<!--				<label class="title">Категория</label>-->
<!--				<select name="category">-->
<!--					<option value="0" --><?//= $product['category'] == 0 ? 'selected' : '' ?><!-- --- категория не выбрана --- </option>-->
<!--                    --><?php //foreach ($data['categories'] ?? [] as $category): ?>
<!--						<option value="--><?//= $category->id ?><!--"-->
<!--								class="parentCategory_--><?//= $category['parent_id'] ?><!--"-->
<!--                            --><?//= $product['category'] == $category->id ? 'selected' : '' ?>
<!--						>--><?//= $category['parent_category_title_ru'] . ' > ' . $category['category_title_ru'] ?><!--</option>-->
<!--                    --><?php //endforeach; ?>
<!--				</select>-->
<!--			</div>-->
			<div class="selectField inputBox">
				<label class="title">Пол</label>
				<select name="gender">
					<option value="0" <?= $product->gender === 0 ? 'selected' : '' ?>>Унисекс</option>
					<option value="1" <?= $product->gender === 1 ? 'selected' : '' ?>>Для мальчика</option>
					<option value="2" <?= $product->gender === 2 ? 'selected' : '' ?>>Для девочки</option>
				</select>
			</div>
			<div class="selectField inputBox">
				<label class="title">Производитель</label>
				<select name="producer">
					<option value="0" <?= $product->producer == 0 ? 'selected' : '' ?>>Не указан</option>
					<option value="1" <?= $product->producer == 1 ? 'selected' : '' ?>>Солнышко (Комсомольск, Украина) </option>
				</select>
			</div>
            <h2>Фотографии</h2>
            <?php foreach ($product->imgs as $img): ?>
                <div class="fileField">
                    <label>
                        <input name="images[]" type="file" data-crop-img="1200"
                               data-img-src="/<?= $img ?>">
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="fixedSubmit" type="submit"><?= $product->id ? 'Редактировать' : 'Добавить' ?></button>
    </form>
	<script>
		var ActionProduct = {
		    handlers: {
		        "#parentCategory:change": function() {
		            var childCategory = $('#childCategory');
                    childCategory.find('li').hide();
                    childCategory.find('.parentCategory_' + this.value).show();
				},
			}
		}
	</script>
</div>
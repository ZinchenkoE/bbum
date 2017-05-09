<?php
/** @var $data array */
$is_put = $data['action'] == 'put';
$new_product = [
		'product_id'     => '',
		'title_ru'       => '',
		'title_uk'       => '',
		'description_ru' => '',
		'description_uk' => '',
		'product_status' => 1,
		'gender'         => 0,
		'producer'       => 0,
		'price'          => '',
		'category'       => 0
];
$product    = $is_put ? $data['product'] : $new_product;
?>
<style>
    .fileField{ display: inline-block; }
</style>
<div class="formPage" data-objs="ActionProduct" id="ActionProduct">
    <form action="/admin/product/<?= $is_put ? $product['product_id'] : 'new' ?>" method="<?= $data['action'] ?>">
        <h1 class="pageTitle"><?= $is_put ? 'Редактирование товара №'.$product['product_id'] : 'Добавление товара' ?></h1>
        <div class="leftCol">
            <div class="textField inputBox">
                <input placeholder="Название RU" id="title_ru" name="title_ru" type="text" value="<?= $product['title_ru'] ?>" pattern="text" required>
            </div>
            <div class="textField inputBox">
                <input placeholder="Название UA" id="title_uk" name="title_uk" type="text" value="<?= $product['title_uk'] ?>" pattern="text" required>
            </div>
            <div class="textField inputBox">
                <textarea placeholder="Описание RU" id="description_ru" name="description_ru" pattern="text" required><?= $product['description_ru'] ?></textarea>
            </div>
            <div class="textField inputBox">
                <textarea placeholder="Описание UA" id="description_uk" name="description_uk" pattern="text" required><?= $product['description_uk'] ?></textarea>
            </div>
			<div class="textField inputBox">
				<input placeholder="Цена" id="price" name="price" type="text" value="<?= $product['price'] ?>" pattern="integer" required>
			</div>
			<div class="switch withLabel">
				<label>
					<span class="title">Статус</span>
					<input name="product_status" type="checkbox" <?= $product['product_status'] ? 'checked' : '' ?>>
					<span class="lever"></span>
				</label>
			</div>
        </div>
        <div class="rightCol">
			<div class="selectField inputBox">
				<label class="title">Родительская категория</label>
				<select id="parentCategory">
					<option value="0" selected> --- категория не выбрана --- </option>
                    <?php foreach ($data['parent_categories'] ?? [] as $parent_category): ?>
						<option value="<?= $parent_category['parent_category_id'] ?>">
							<?= $parent_category['parent_category_title_ru'] ?>
						</option>
                    <?php endforeach; ?>
				</select>
			</div>
			<div class="selectField inputBox" id="childCategory">
				<label class="title">Категория</label>
				<select name="category">
					<option value="0" <?= $product['category'] == 0 ? 'selected' : '' ?>> --- категория не выбрана --- </option>
                    <?php foreach ($data['categories'] ?? [] as $category): ?>
						<option value="<?= $category['category_id'] ?>"
								class="parentCategory_<?= $category['parent_id'] ?>"
                            <?= $product['category'] == $category['category_id'] ? 'selected' : '' ?>
						><?= $category['parent_category_title_ru'] . ' > ' . $category['category_title_ru'] ?></option>
                    <?php endforeach; ?>
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
					<option value="0" <?= $product['producer'] == 0 ? 'selected' : '' ?>>Не указан</option>
					<option value="1" <?= $product['producer'] == 1 ? 'selected' : '' ?>>Солнышко (Комсомольск, Украина) </option>
				</select>
			</div>
            <h2>Фотографии</h2>
            <?php for ($i = 0; $i < 6; $i++): ?>
                <div class="fileField">
                    <label>
                        <input name="images_<?= $i ?>" type="file" data-crop-img="1200"
                               data-img-src="<?= isset($data['images'][$i]) ? '/' . $data['images'][$i]['src'] : '' ?>">
                    </label>
                </div>
            <?php endfor; ?>
        </div>
        <button class="fixedSubmit" type="submit"><?= $is_put ? 'Редактировать' : 'Добавить' ?></button>
    </form>
	<script>
		var ActionProduct = {
		    handlers: {
		        "#parentCategory:change": function() {
                   $('#childCategory li').hide();
                   $('#childCategory .parentCategory_' + this.value).show();
                    console.log('#childCategory .parentCategory_'+this.value);
				},
			}
		}
	</script>
</div>
<?php
/** @var $data array */
?>
<div class="formPage" data-objs="Order" id="Order">
	<th class="customerName">КЛИЕНТ</th>
	<th class="email"		>E-MAIL</th>
	<th class="phone"		>ТЕЛЕФОН</th>
	<th class="city"		>ГОРОД</th>
	<th class="stock"		>СКЛАД</th>
	<th class="status"		>СТАТУС</th>
	<th class="btns"		>ПРОСМОТР</th>
    <form action="/admin/order/<?= $data['order_id'] ?>" method="put">
        <h1 class="pageTitle">Редактирование заказа № <?= $data['product_id'] ?></h1>
        <div class="leftCol">
            <div class="textField inputBox">
                <input placeholder="Клиент" id="title_ru" name="title_ru" type="text" value="<?= $data['title_ru'] ?>" pattern="text" required>
            </div>
            <div class="textField inputBox">
                <input placeholder="E-mail" id="title_uk" name="title_uk" type="text" value="<?= $data['title_uk'] ?>" pattern="text" required>
            </div>
            <div class="textField inputBox">
                <textarea placeholder="Телефон" id="description_ru" name="description_ru" pattern="text" required><?= $data['description_ru'] ?></textarea>
            </div>
            <div class="textField inputBox">
                <textarea placeholder="Город" id="description_uk" name="description_uk" pattern="text" required><?= $data['description_uk'] ?></textarea>
            </div>
			<div class="textField inputBox">
				<input placeholder="Цена" id="price" name="price" type="text" value="<?= $data['price'] ?>" pattern="integer" required>
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
					<option value="0" <?= $data['category'] == 0 ? 'selected' : '' ?>> --- категория не выбрана --- </option>
                    <?php foreach ($data['categories'] ?? [] as $category): ?>
						<option value="<?= $category['category_id'] ?>"
								class="parentCategory_<?= $category['parent_id'] ?>"
                            <?= $data['category'] == $category['category_id'] ? 'selected' : '' ?>
						><?= $category['parent_category_title_ru'] . ' > ' . $category['category_title_ru'] ?></option>
                    <?php endforeach; ?>
				</select>
			</div>
			<div class="selectField inputBox">
				<label class="title">Пол</label>
				<select name="gender">
					<option value="0" <?= $data['gender'] == 0 ? 'selected' : '' ?>>Унисекс</option>
					<option value="1" <?= $data['gender'] == 1 ? 'selected' : '' ?>>Для мальчика</option>
					<option value="2" <?= $data['gender'] == 2 ? 'selected' : '' ?>>Для девочки</option>
				</select>
			</div>
			<div class="selectField inputBox">
				<label class="title">Производитель</label>
				<select name="producer">
					<option value="0" <?= $data['producer'] == 0 ? 'selected' : '' ?>>Не указан</option>
					<option value="1" <?= $data['producer'] == 1 ? 'selected' : '' ?>>Солнышко (Комсомольск, Украина) </option>
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
		var Order = {
		    handlers: {
		        "#parentCategory:change": function() {
                   $('#childCategory li').hide();
                   $('#childCategory .parentCategory_' + this.value).show();
                    console.log('#childCategory .parentCategory_'+this.value);
				},
			}
		}
        $('#Cart-citySelect option:eq(1)').each(function() {
            var fd = new FormData;
            fd.append('city_id', this.value);
            fd.append('city_name', this.innerText);
            a.Query.post({url: '/city', data:fd });
​
        })
	</script>
</div>
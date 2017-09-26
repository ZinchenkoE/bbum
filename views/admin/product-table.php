<?php
/** @var array $data  */
//echo '<pre>'; print_r($data['products'] );die;
?>
<div id="ProductTablePage" data-objs="ProductTablePage">
    <h1 class="pageTitle">Товары</h1>
    <table>
        <thead>
            <tr>
                <th class="productId">id</th>
                <th class="productName">НАЗВАНИЕ</th>
                <th class="category">Пол</th>
                <th class="category">КАТЕГОРИЯ</th>
                <th class="status">СТАТУС</th>
                <th class="btns">НАСТРОЙКИ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['products'] ?? [] as $product): ?>
                <tr product-id="<?= $product['product_id'] ?>">
                    <td><?= $product['product_id'] ?></td>
                    <td><?= $product->title ?></td>
                    <td>
                        <?
                            switch ($product['gender']) {
                                case 0: echo 'Унисекс'; break;
                                case 1: echo 'Для мальчиков'; break;
                                case 2: echo 'Для девочек'; break;
                            }
                        ?>
                    </td>
                    <td><?= $product['parent_category_title_ru'] . ' > ' . $product['category_title_ru'] ?></td>
                    <? if(!'Режим назначения категории'): ?>
                        <td>
                            <div class="selectField inputBox">
                                <select name="category">
                                    <option value="0" <?= $product['category'] == 0 ? 'selected' : '' ?>> --- категория не выбрана --- </option>
                                        <?php foreach ($data['categories'] ?? [] as $category): ?>
                                            <?php if (($product['gender']== 1 && $category['parent_category_id'] == 1) ||
                                                ($product['gender']== 2 && $category['parent_category_id'] == 2) || $product['gender'] == 0 ): ?>
                                                <option value="<?= $category->id ?>"
                                                    <?= $product['category'] == $category->id ? 'selected' : '' ?>
                                                ><?= $category['parent_category_title_ru'] . ' > ' . $category['category_title_ru'] ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                </select>
                            </div>
                        </td>
                    <? endif; ?>
                    <td class="status">
                       <div class="switch">
                           <label>
                               <input class="js-changeStatus" type="checkbox"
								   <?= $product['product_status'] == \app\models\Product::STATUS_ACTIVE ? 'checked' : '' ?>
							   >
                               <span class="lever"></span>
                           </label>
                       </div>
                    </td>
					<td class="btns">
                        <a href="/admin/product/<?= $product['product_id'] ?>"><i class="icon icon-settings-black"></i></a>
                        <i class="icon icon-trash-black js-delProduct"></i>
					</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<?php if(isset($data['pages'])): ?>
        <?= \yii\widgets\LinkPager::widget([
            'pagination' => $data['pages'],
            'maxButtonCount' => 10,
            'lastPageLabel'  => false,
            'firstPageLabel' => false,
            'nextPageLabel'  => false,
            'prevPageLabel'  => false,
        ]);?>
    <?php endif; ?>
    <div class="plusBtn" href="/admin/product/new">+</div>
    <script>
        ProductTablePage = {
            handlers: {
                "#ProductTablePage .js-delProduct:click" : function() {
                    var productId = $(this).closest('tr').attr('product-id');
                    a.ConfirmBox({
                        title: 'Вы дествительно хотите удалить этот товар?',
                        action: '/admin/product/' + productId
                    });
                },
                "#ProductTablePage .js-changeStatus:change" : function() {
                    var productId = $(this).closest('tr').attr('product-id');
                    var fd = new FormData();
                    var status = +$(this).prop('checked');
                    fd.append('_prm', 'changeStatus');
                    fd.append('product_status', status);
                    a.Query.post({url: '/admin/product/' + productId, data: fd});
				},
                "#ProductTablePage [name='category']:change": function() {
                    var productId = $(this).closest('tr').attr('product-id');
                    var fd = new FormData();
                    fd.append('_prm', 'setCategory');
                    fd.append('category', this.value);
                    a.Query.post({url: '/admin/product/' + productId, data: fd});
                }
            }
        };
    </script>
</div>





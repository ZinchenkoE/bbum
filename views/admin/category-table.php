<?php
/** @var array $data  */
?>
<div id="CategoryTablePage" data-objs="CategoryTablePage" style="margin-bottom: 200px;">
    <h1 class="pageTitle">Категории</h1>
        <?php foreach($data['parent_categories'] ?? [] as $parent): ?>
            <table>
                <thead>
                    <tr>
						<th class="categoryId">id</th>
                        <th><?= $parent['parent_title_ru'] ?></th>
                        <th>ru</th>
                        <th>uk</th>
						<th class="status">СТАТУС</th>
                        <th class="btns"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['categories'] ?? [] as $category): ?>
                        <?php if ($category['parent_id'] == $parent['parent_category_id']): ?>
                            <tr category-id="<?= $category->id ?>" parent-id="<?=$category['parent_id']?>">
								<td><?= $category->id ?></td>
                                <td class="categoryName"><?= $category['parent_title_ru'] . ' > ' . $category->title ?></td>
                                <td><input class="js-categoryTitle" name="title_ru" value="<?= $category->title_ru ?>"></td>
                                <td><input class="js-categoryTitle" name="title_uk" value="<?= $category->title_uk ?>"></td>
								<td class="status">
									<div class="switch">
										<label>
											<input class="js-changeStatus" type="checkbox"
                                                <?= $category->status == \app\models\Category::STATUS_ACTIVE ? 'checked' : '' ?>
											>
											<span class="lever"></span>
										</label>
									</div>
								</td>
                                <td class="btns">
                                    <i class="icon icon-settings-black" href="/admin/category/<?= $category->id ?>"></i>
                                    <i class="icon icon-trash-black js-delCategory"></i>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    <div class="plusBtn" href="/admin/category/new">+</div>
	<script>
		var CategoryTablePage = {
		    handlers: {
                "#CategoryTablePage .js-delCategory:click" : function() {
                    var categoryId = $(this).closest('tr').attr('category-id');
                    a.ConfirmBox({
                        title: 'Вы дествительно хотите удалить эту категорию?',
                        action: '/admin/category/' + categoryId
                    });
                },
                "#CategoryTablePage js-categoryTitle:change" : function() {
                    var categoryId = $(this).closest('tr').attr('category-id');
                    var parentId = $(this).closest('tr').attr('parent-id');
                    var fd = new FormData();
                    fd.append('title_ru', $(this).closest('tr').find('[name="title_ru"]').val());
                    fd.append('title_uk', $(this).closest('tr').find('[name="title_uk"]').val());
                    fd.append('parent_id', parentId);
                    a.Query.post({url: '/admin/category/' + categoryId, data: fd});
				},
                "#CategoryTablePage .js-changeStatus:change" : function() {
                    var categoryId = $(this).closest('tr').attr('category-id');
                    var fd = new FormData();
                    var status = +$(this).prop('checked');
                    fd.append('_prm', 'changeStatus');
                    fd.append('category_status', status);
                    a.Query.post({url: '/admin/category/' + categoryId, data: fd});
                },
			},
		};
	</script>
</div>

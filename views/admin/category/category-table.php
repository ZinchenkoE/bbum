<?php ?>
<div class="CategoryTablePage" data-objs="CategoryTablePage">
    <h1 class="pageTitle">Категории</h1>
    <?php if (isset($data['parent_categories'])): ?>
        <?php foreach($data['parent_categories'] as $parent): ?>
            <table>
                <thead>
                    <tr>
                        <th class="categoryName"><?= $parent['parent_category_title_ru'] ?></th>
                        <th class="btns"></th>
                    </tr>
                </thead>
                <tbody>
                <?php if (isset($data['categories'])): ?>
                    <?php foreach($data['categories'] as $category): ?>
                        <?php if ($category['parent_id'] == $parent['parent_category_id']): ?>
                            <tr data-category-id="<?= $category['category_id'] ?>">
                                <td class="categoryName"><?= $category['parent_category_title_ru'] . ' > ' . $category['category_title_ru'] ?></td>
                                <td class="btns">
                                    <i class="icon icon-settings-black" href="/admin/category/<?= $category['category_id'] ?>"></i>
                                    <i class="icon icon-trash-black js-delCategory"></i>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="plusBtn" href="/admin/category/new">+</div>
    <script>
        $A.CategoryTablePage = {
            handlers: {
                ".js-delCategory:click"                : function() {
                    var categoryId = $(this).closest('tr').attr('data-category-id');
                    $A.ConfirmBox({
                        title: 'Вы дествительно хотите удалить эту категорию?',
                        action: '/admin/category/' + categoryId
                    });
                },
            }
        };
    </script>
</div>

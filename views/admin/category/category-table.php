<?php ?>
<div class="categoryTablePage" data-objs="CategoryTablePage">
    <h1 class="pageTitle">Категории</h1>
    <?php if (isset($data['parent_categories'])): ?>
        <?php foreach($data['parent_categories'] as $parent): ?>
            <table>
                <thead>
                    <tr>
                        <th><?= $parent['parent_category_title_ru'] ?></th>
                        <th>ru</th>
                        <th>uk</th>
                        <th class="btns"></th>
                    </tr>
                </thead>
                <tbody>
                <?php if (isset($data['categories'])): ?>
                    <?php foreach($data['categories'] as $category): ?>
                        <?php if ($category['parent_id'] == $parent['parent_category_id']): ?>
                            <tr category-id="<?= $category['category_id'] ?>" parent-id="<?=$category['parent_id']?>">
                                <td class="categoryName"><?= $category['parent_category_title_ru'] . ' > ' . $category['category_title_ru'] ?></td>
                                <td><input name="category_title_ru" value="<?= $category['category_title_ru'] ?>" ></td>
                                <td><input name="category_title_uk" value="<?= $category['category_title_uk'] ?>" ></td>
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
</div>

<?php
/** @var array $data  */
use app\components\Lng;
$lng = Lng::getLng();
?>
<div class="row">
    <a href="/<?= $lng ?>/category?cat=1" class="category half boy left">
        <h2><?= Lng::t('Одежда для мальчиков') ?></h2>
        <div class="btn">За покупками</div>
    </a>
    <a href="/<?= $lng ?>/category?cat=2" class="category half girl right">
        <h2><?= Lng::t('Одежда для девочек') ?></h2>
        <div class="btn">За покупками</div>
    </a>
</div>
<div class="row">
    <a href="/<?= $lng ?>/category?cat=39" class="category toys с-3" style="background: url(/res/imgs/122_0.jpg) center center / contain no-repeat;">
        <h2><?= Lng::t('Пижамы') ?></h2>
    </a>
    <a href="/<?= $lng ?>/category-tag?cat=11" class="category winter с-3" style="background: url(/res/imgs/13_0.jpg) center center / cover no-repeat;" >
        <h2><?= Lng::t('Футболки') ?></h2>
    </a>
    <a href="/<?= $lng ?>/category?cat=35" class="category linen с-3" style="background: url(/res/imgs/183_0.jpg) center center / cover no-repeat;">
        <h2><?= Lng::t('Кофты') ?></h2>
    </a>
</div>

<?= Yii::$app->controller->renderPartial('/site/index/recommendBlock', ['data' => $data]); ?>
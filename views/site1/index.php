<?php
/** @var $data array **/
$lng = Yii::$app->lng->getLng();
$w   = Yii::$app->lng->getDictionary();

?>
<div class="row">
    <a href="/<?= $lng ?>/category/1" class="category half boy left">
        <h2><?= $w['clothes-for-boy'] ?></h2>
        <div class="btn">За покупками</div>
    </a>
    <a href="/<?= $lng ?>/category/2" class="category half girl right">
        <h2><?= $w['clothes-for-girl'] ?></h2>
        <div class="btn">За покупками</div>
    </a>
</div>
<div class="row">
    <a href="/<?= $lng ?>/category-tag/sleepwear" class="category toys с-3" style="background: url(/res/imgs/122_0.jpg) center center / contain no-repeat;">
        <h2><?= $w['sleepwear'] ?></h2>
    </a>
    <a href="/<?= $lng ?>/category-tag/t-shirt" class="category winter с-3" style="background: url(/res/imgs/13_0.jpg) center center / cover no-repeat;" >
        <h2>Футболки</h2>
    </a>
    <a href="/<?= $lng ?>/category/2/73" class="category linen с-3" style="background: url(/res/imgs/183_0.jpg) center center / cover no-repeat;">
        <h2><?= $w['for-girl'] ?></h2>
    </a>
</div>

<?= Yii::$app->controller->renderPartial('/site/index/recommendBlock', ['data' => $data]); ?>
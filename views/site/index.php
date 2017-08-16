<?php
/** @var $data array **/
$lng = Yii::$app->lng->getLng();
$w   = Yii::$app->lng->getDictionary();

?>

<ul class="productCategory">
    <li class="category">
        <a href="/<?= $lng ?>/category/1" class="locationCategory womenBoots"></a>
        <span class="titleCategory"><?= $w['clothes-for-girl'] ?></span>
    </li>
    <li class="category">
        <a href="/<?= $lng ?>/category/2" class="locationCategory teenBoots"></a>
        <span class="titleCategory"><?= $w['clothes-for-boy'] ?></span>
    </li>
    <li class="category">
        <a href="/<?= $lng ?>/category/3" class="locationCategory kidBoots"></a>
        <span class="titleCategory"><?= $w['clothes-for-baby'] ?></span>
    </li>
    <li class="category">
        <a href="/<?= $lng ?>/category/4" class="locationCategory menBoots"></a>
        <span class="titleCategory">Игрушки</span>
    </li>
</ul>
<script>$('main').addClass('pageMain');</script>
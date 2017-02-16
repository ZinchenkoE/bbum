<?php
/**
*   @var $data array
**/
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

<div class="recommend">
    <h3><?= $w['recommend'] ?></h3>
    <div class="slider" data-last-page="3" data-position="0">
        <div class="prev sliderBtn"></div>
        <div class="sliderInner row">
            <? if(isset($data['product_recommend'])): ?>
            <? foreach ($data['product_recommend'] as $product): ?>
                <a href="/<?= $lng ?>/product/<?= $product['product_id']?>" class="item <?= $product['product_id']%2==0  ? 'new' : '' ?>">
                    <div class="imgBox" style="background: url(/<?= $product['img_src'] ?>) center center / contain no-repeat;">
                        <div class="substrate">
                            <button><?= $w['bay'] ?></button>
                        </div>
                    </div>
                    <h4 class="title"><?= $product['title_'.$lng] ?></h4>
                    <h5 class="subTitle"><?= $product['category_title_'.$lng] ?></h5>
                    <div class="price"><?= $product['price'] ?> грн</div>
                </a>
            <? endforeach; ?>
            <? endif; ?>
        </div>
        <div class="next sliderBtn"></div>
    </div> 
</div>


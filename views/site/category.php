<?
$lng = Yii::$app->lng->getLng();
$w   = Yii::$app->lng->getDictionary();

$price_from = (int)Yii::$app->request->get('price_from');
$price_to   = (int)Yii::$app->request->get('price_to');
?>
<? if($data['show-gender-filter']): ?>
<div class="row" style="margin-bottom: 50px;">
    <input class="genderFilter" type="checkbox" id="for_unisex" <?= (bool)Yii::$app->request->get('for_unisex') ? 'checked' : '' ?>><label for="forUnisex"><?= $w['unisex']   ?></label>
    <input class="genderFilter" type="checkbox" id="for_boy"    <?= (bool)Yii::$app->request->get('for_boy')    ? 'checked' : '' ?>><label for="forBoy"   ><?= $w['for-boy']  ?></label>
    <input class="genderFilter" type="checkbox" id="for_girl"   <?= (bool)Yii::$app->request->get('for_girl')   ? 'checked' : '' ?>><label for="forGirl"  ><?= $w['for-girl'] ?></label>
</div>
<? endif; ?>

<div class="row" style="margin-bottom: 50px;">
    <div id="priceRange"
         style="width: 500px; margin-bottom: 20px;"
         data-min="<?= $data['min-price'] ?>"
         data-max="<?= $data['max-price'] ?>"
         data-value-min="<?= $price_from ? $price_from : 0 ?>"
         data-value-max="<?= $price_to   ? $price_to   : $data['max-price'] ?>"
    ></div>
    <p>
        <label for="amount">Укажите размах цен:</label>
        <input type="text" id="amount" style="border:0; color:#f6931f; background:#fff;font-weight:bold;" disabled>
    </p>
</div>

<div class="row">
    <? foreach ($data['products'] as $product): ?>
        <a href="/<?= $lng ?>/product/<?= $product['product_id'] ?>" class="productInCategory с-3" style="background: url(/<?= $product['img_src'] ?>) center center / cover no-repeat;">
            <h2><?= $product['title_'.$lng] ?> <br> <?= $w['price'] ?> : <?= $product['price'] ?> грн </h2>
        </a>
    <? endforeach; ?>
</div>

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
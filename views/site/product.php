<?
$lng = Yii::$app->lng->getLng();
$w   = Yii::$app->lng->getDictionary();
?>

<div class="productPage">
    <div class="row">
        <div class="leftPart">
            <ul id="imageGallery">
                <? foreach ($data['images'] as $img): ?>
                    <li data-thumb="/<?= $img['src'] ?>" data-src="/<?= $img['src'] ?>">
                        <img src="/<?= $img['src'] ?>" />
                    </li>
                <? endforeach; ?>
            </ul>
        </div>
        <div class="rightPart">
            <p>Артикул: 100-<?= $data['product']['product_id'] ?></p>
            <p><?= $w['title'] ?>: <?= $data['product']['title_'.$lng] ?></p>
            <p><?= $w['description'] ?>: <?= $data['product']['description_'.$lng] ?></p>
            <p><?= $w['price'] ?>: <?= $data['product']['price'] ?> грн</p>
        </div>
    </div>
</div>




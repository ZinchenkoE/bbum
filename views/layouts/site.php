<?php
use yii\helpers\Html;
/** * @var $content */
$category = \app\models\SiteCategory::getCategoriesForMenu();
$category_for_boy  = array_filter($category, function($i) { return ($i['parent_id'] == 1); });
$category_for_girl = array_filter($category, function($i) { return ($i['parent_id'] == 2); });

$lng = Yii::$app->lng->getLng();
$w   = Yii::$app->lng->getDictionary();

?>
<!DOCTYPE html>
<html lang="<?= $lng ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>Baby Bum in Ukraine</title>

    <link rel="stylesheet" href="http://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="/css/lightslider.min.css">
    <link rel="stylesheet" href="/css/lightgallery.min.css">
    <link rel="stylesheet" href="/css/jquery-ui.min.css">
    <link rel="stylesheet" href="/css/site.css">

    <script src="/js/lib/jquery-3.1.1.min.js"></script>
    <script src="/js/lib/lightslider.min.js"></script>
    <script src="/js/lib/maskedInput.js"></script>
    <script src="/js/lib/lightgallery.min.js"></script>
    <script src="/js/lib/jquery-ui.min.js"></script>

    <script src="/js/a_site.js"></script>
	<script>
		a.params.lang = '<?= $lng ?>';
	</script>

    <script src="/js/a.Validator.js"></script>
    <script src="/js/a.Select.js"></script>
	<script src="/js/a.Query.js"></script>
    <script src="/js/devTools.js"></script>

</head>
<body>
    <header>
        <div class="container row">
            <a href="/<?= $lng ?>" id="logo"></a>
            <div class="contacts row">
                <div class="left">
                    <p><img src="/img/site/phone-call.svg"><span>+380-66-323-05-29</span></p>
                    <p><img src="/img/site/phone-call.svg"><span>+380-68-164-41-33</span></p>
                </div>
                <div class="right">
                    <p><img src="/img/site/envelope.svg"><span>sale@baby-bum.in.ua</span></p>
                </div>
            </div>

        </div>
        <nav>
            <ul class="container">
                <li><a href="/<?= $lng ?>"><?= $w['home'] ?></a></li>
                <li>
                    <a href="#"><?= $w['for-boy'] ?></a>
                    <ul>
                        <? foreach ($category_for_boy as $boy_cat): ?>
                        <li><a href="/<?= $lng ?>/category/<?= $boy_cat['parent_id']?>/<?= $boy_cat['category_id']?>"
                            ><?= $boy_cat['category_title_'.$lng]?></a></li>
                        <? endforeach; ?>
                    </ul>
                </li>
                <li>
                    <a href="#"><?= $w['for-girl'] ?></a>
                    <ul>
                        <? foreach ($category_for_girl as $boy_girl): ?>
                            <li><a href="/<?= $lng ?>/category/<?= $boy_girl['parent_id']?>/<?= $boy_girl['category_id']?>"
                                ><?= $boy_girl['category_title_'.$lng]?></a></li>
                        <? endforeach; ?>
                    </ul>
                </li>
                <li><a href="/<?= $lng ?>/info"><?= $w['payment-delivery'] ?></a></li>
                <li><a href="/<?= $lng ?>/contacts"><?= $w['contacts'] ?></a></li>
				<i class="material-icons" onclick="Cart.show()"
				   style="vertical-align: middle; cursor: pointer; margin-left: 20px; color: #545454;"
				>shopping_cart</i>
            </ul>

        </nav>
    </header>
    <main class="container"><?= $content ?></main>
    <footer>

    </footer>
    <div id="overlay"></div>
    <?= Yii::$app->controller->renderPartial('/site/partial/preloader')?>
    <?= Yii::$app->controller->renderPartial('/site/partial/cart')?>
    <script> a.init(); </script>
</body>
</html>

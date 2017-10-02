<?php
use yii\helpers\Html;
use app\models\Category;
/** *
 * @var $content
 */
use app\components\Lng;

$lng = Lng::getLng();

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
            <i class="material-icons" onclick="Cart.show()"
               style="vertical-align: middle; cursor: pointer; margin-left: 20px; color: #545454;line-height: 100px; float: right;"
            >shopping_cart</i>
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
                <li><a href="/<?= $lng ?>"><?= Lng::t('Главная') ?></a></li>
                <? foreach (Category::getRootCategory() as $cat): ?>
                    <li>
                        <a href="#"><?= $cat->title_ru ?></a>
                        <ul>
                            <? foreach ($cat->children as $children_cat): ?>
                                <li><a href="/<?= $lng ?>/category?cat=<?= $children_cat->id ?>"
                                    ><?= $children_cat->title_ru ?></a>
                                </li>
                            <? endforeach; ?>
                        </ul>
                    </li>
                <? endforeach; ?>
                <li><a href="/<?= $lng ?>/info"><?= Lng::t('Доставка и оплата') ?></a></li>
                <li><a href="/<?= $lng ?>/contacts"><?= Lng::t('Контакты') ?></a></li>
            </ul>
        </nav>
    </header>
    <?= Yii::$app->controller->renderPartial('/site/partial/cart')?>
	<main class="container"><?= $content ?></main>
    <footer>
        u>li
    </footer>
    <div id="overlay"></div>
    <?= Yii::$app->controller->renderPartial('/site/partial/preloader')?>
    <script> a.init(); </script>
</body>
</html>

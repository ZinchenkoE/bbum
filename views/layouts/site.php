<?php
use yii\helpers\Html;
/** * @var $content */
$lng = Yii::$app->lng->getLng();
$w   = Yii::$app->lng->getDictionary();
?>

<!DOCTYPE html>
<html lang="<?= $lng ?>">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
	<title>Baby Bum in Ukraine</title>

	<link href="/css/site.css" rel="stylesheet">
	<link href="/plugins/scrollbar/css/jquery.mCustomScrollbar.css" rel="stylesheet">

	<script src="/js/lib/jquery-3.1.1.min.js"></script>
	<script src="/js/menu.js"></script>
	<script src="/js/bag.js"></script>
	<script src="/js/validForm.js"></script>
	<script src="/plugins/responsiveSlides/js/responsiveslides.min.js"></script>
	<script src="/plugins/jquery_bxslider/jquery.bxslider.min.js"></script>
	<script src="/js/script.js"></script>
	<script src="/plugins/scrollbar/js/jquery.mCustomScrollbar.concat.min.js"></script>

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
	<div id="wrapper">
		<div class="wrappMenu">
			<div class="verticalPosition">
				<ul class="menu">
					<li class="itemMenu">
						<a href="#" class="linkMenu">Категории товаров</a>
                        <ul class="submenu">
                            <li class="itemMenu itemSubmenu">
                                <a href="#" class="linkMenu linkSubmenu">Для девочек</a>
                            </li>
                            <li class="itemMenu itemSubmenu">
                                <a href="#" class="linkMenu linkSubmenu">Для мальчиков</a>
                            </li>
                            <li class="itemMenu itemSubmenu">
                                <a href="#" class="linkMenu linkSubmenu">Для младенцев</a>
                            </li>
                            <li class="itemMenu itemSubmenu">
                                <a href="#" class="linkMenu linkSubmenu">Игрушки</a>
                            </li>
                        </ul>
                    </li>
					<li class="itemMenu">
						<a href="#" class="linkMenu">покупателям</a>
						<ul class="submenu">
							<li class="itemMenu itemSubmenu">
								<a href="#" class="linkMenu linkSubmenu">как купить?</a>
							</li>
							<li class="itemMenu itemSubmenu">
								<a href="#" class="linkMenu linkSubmenu">оплата и доставка</a>
							</li>
							<li class="itemMenu itemSubmenu">
								<a href="#" class="linkMenu linkSubmenu">обмен и возврат</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<div class="rightSitebar">
			<div class="header">
				<a href="index.html" class="logo"></a>
			</div>
			<nav class="pageMenu">
				<ul class="mainMenu">
					<li class="itemMainMenu">
						<a href="#" class="linkMainMenu goToMenu"></a>
					</li>
<!--					<li class="itemMainMenu">-->
<!--						<a href="autorization.html" class="linkMainMenu goToLogin"></a>-->
<!--					</li>-->
					<li class="itemMainMenu">
						<a href="contacts.html" class="linkMainMenu gotoPhone"></a>
					</li>
					<li class="itemMainMenu">
						<a href="#" class="linkMainMenu goToBag"><span class="pulseBag"></span></a>
					</li>
				</ul>
			</nav>
			<div class="boxBottomFix">
				<ul class="checkLang">
					<li class="lang langRu <?= $lng === 'ru' ? 'activeLang' : '' ?>"><a href="/ru">ru</a></li>
					<li class="lang langUa <?= $lng === 'uk' ? 'activeLang' : '' ?>"><a href="/uk">ua</a></li>
				</ul>
				<ul class="social">
					<li class="boxIconSocial"><a href="#" class="socialIcon vkontakte"></a></li>
					<li class="boxIconSocial"><a href="#" class="socialIcon facebook"></a></li>
					<li class="boxIconSocial"><a href="#" class="socialIcon instagram"></a></li>
				</ul>
			</div>
		</div>
		<div class="overlay"></div>
        <?= Yii::$app->controller->renderPartial('/site/cart')?>
		<main class="content pageMain"><?= $content ?></main>
	</div>
</body>
</html>
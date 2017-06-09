<?php
use yii\helpers\Html;
/** * @var $content */
$lng = Yii::$app->lng->getLng();
$w   = Yii::$app->lng->getDictionary();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
	<title>Alisa</title>
	<link rel="stylesheet" href="../web/css/styles.css">
	<script src="./js/lib/jquery-3.1.1.min.js"></script>
	<script src="./js/menu.js"></script>
	<script src="./js/bag.js"></script>
	<script src="./js/validForm.js"></script>
	<script src="./plugins/responsiveSlides/js/responsiveslides.min.js"></script>
	<script src="./plugins/jquery_bxslider/jquery.bxslider.min.js"></script>
	<script src="./js/script.js"></script>
	<script src="./plugins/scrollbar/js/jquery.mCustomScrollbar.concat.min.js"></script>
	<link  href="./plugins/scrollbar/css/jquery.mCustomScrollbar.css" rel="stylesheet">
</head>
<body>
	<div id="wrapper">
		<div class="wrappMenu">
			<div class="verticalPosition">
				<ul class="menu">
					<li class="itemMenu">
						<a href="index.html" class="linkMenu">Категории товаров</a>
						</li>
					<li class="itemMenu">
						<a href="#" class="linkMenu">о компании</a>
						<ul class="submenu">
							<li class="itemMenu itemSubmenu">
								<a href="#" class="linkMenu linkSubmenu">история компании</a>
							</li>
							<li class="itemMenu itemSubmenu">
								<a href="#" class="linkMenu linkSubmenu">приемущества</a>
							</li>
							<li class="itemMenu itemSubmenu">
								<a href="#" class="linkMenu linkSubmenu">пресса о нас</a>
							</li>
							<li class="itemMenu itemSubmenu">
								<a href="#" class="linkMenu linkSubmenu">производство обуви</a>
							</li>
						</ul>
					</li>
					<li class="itemMenu">
						<a href="#" class="linkMenu">новости</a>
						<ul class="submenu">
							<li class="itemMenu itemSubmenu">
								<a href="#" class="linkMenu linkSubmenu">график выставок</a>
							</li>
							<li class="itemMenu itemSubmenu">
								<a href="#" class="linkMenu linkSubmenu">статьи</a>
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
					<li class="itemMenu">
						<a href="#" class="linkMenu">сотрудничество</a>
						<ul class="submenu">
							<li class="itemMenu itemSubmenu">
								<a href="#" class="linkMenu linkSubmenu">почему мы лучше?</a>
							</li>
							<li class="itemMenu itemSubmenu">
								<a href="#" class="linkMenu linkSubmenu">условия</a>
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
					<li class="itemMainMenu">
						<a href="autorization.html" class="linkMainMenu goToLogin"></a>
					</li>
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
					<li class="lang langRu activeLang">ru</li>
					<li class="lang langUa">ua</li>
				</ul>
				<ul class="social">
					<li class="boxIconSocial"><a href="#" class="socialIcon vkontakte"></a></li>
					<li class="boxIconSocial"><a href="#" class="socialIcon facebook"></a></li>
					<li class="boxIconSocial"><a href="#" class="socialIcon linkedin"></a></li>
					<li class="boxIconSocial"><a href="#" class="socialIcon instagram"></a></li>
				</ul>
			</div>
		</div>
		<div class="overlay"></div>
		<div class="boxBag">
			<div class="boxBgBag">
				<h2 class="title">Корзина</h2>
			</div>
			<div class="emptyBag">
				<p>Сейчас ваша козина пуста :(</p>
				<p>Перейдите в каталог <br/>что-бы начать добавлять товары.</p>
				<div class="btns">
					<div class="boxBtn">
						<button class="autorizationBtn categoryProdBtn" type="button" onclick="window.location = '#'">Женская обувь</button>
					</div>
					<div class="boxBtn">
						<button class="autorizationBtn categoryProdBtn" type="button" onclick="window.location = '#'">Детская обувь</button>
					</div>
					<div class="boxBtn">
						<button class="autorizationBtn categoryProdBtn" type="button" onclick="window.location = '#'">Подростковая обувь</button>
					</div>
					<div class="boxBtn">
						<button class="autorizationBtn categoryProdBtn" type="button" onclick="window.location = '#'">Обувь для рыбалки и охоты</button>
					</div>
				</div>
			</div>
			<div class="fulBag">
				<div class="boxOrder">
					<div class="total">
						<div class="totalTitle">Итого:</div>
						<div class="sum"><span class="cost">1647</span> грн</div>
					</div>
					<div class="boxBtn">
						<button class="autorizationBtn placeOrder visibleBtn1" type="button"><span class="btnCol1 cost">1600<span> грн</span></span> <span class="btnCol2">оформить заказ</span></button>
					</div>
				</div>
				<div class="boxProduct">
					<div class="description">
						<div class="colLeft productImg">
							<img src="../web/images/img_prod1.png" alt="product model">
						</div>
						<ul class="characteristics">
							<li class="itemCharacteristic titleProduct">Детские ботинки на шнурках</li>
							<li class="itemCharacteristic boxColorProduct">Цвет: <span class="color">коричневый</span></li>
							<li class="itemCharacteristic boxModelProduct">Модель: <span class="modelProduct">ASV_2480</span></li>
							<li class="itemCharacteristic boxPriceProduct">Цена: <span class="priceProduct">100</span> <span class="currency">грн</span></li>
						</ul>
					</div>
					<div class="boxData">
						<div class="boxChangeData">
							<div class="colLeft sizeProduct">
								<span class="changedSize">25</span> <span class="titleSize">размер</span>
							</div>
							<div class="removeRowBtn"></div>
							<ul class="counterProd">
								<li class="countBtn prevCount"><span class="val"></span></li>
								<li class="numberCount"><span class="number">2</span> <span class="titleNumber">пары</span></li>
								<li class="countBtn nextCount"><span class="val"></span></li>
							</ul>
						</div>
						<div class="boxChangeData">
							<div class="colLeft sizeProduct">
								<span class="changedSize">25</span> <span class="titleSize">размер</span>
							</div>
							<div class="removeRowBtn"></div>
							<ul class="counterProd">
								<li class="countBtn prevCount"><span class="val"></span></li>
								<li class="numberCount"><span class="number">2</span> <span class="titleNumber">пары</span></li>
								<li class="countBtn nextCount"><span class="val"></span></li>
							</ul>
						</div>
					</div>
					<div class="boxAddNewProduct">
						<div class="boxBtn">
							<button class="addNewProductBtn" type="button"><span class="iconBtn"></span></button>
						</div>
						<div class="boxSize">
							<h3 class="titleSize">Выберите размер</h3>
							<div class="size">
								<div class="itemSize">
									<div class="numberSize">37</div>
									<div class="lengthSole"><span class="valLength">240</span> мм</div>
									<div class="availability">в наличии</div>
									<div class="boxCounter">
										<div class="titleCounter">Количество</div>
										<ul class="counterProd">
											<li class="countBtn prevCount"><span class="val"></span></li>
											<li class="numberCount"><span class="number">1</span> <span class="titleNumber">пара</span></li>
											<li class="countBtn nextCount"><span class="val"></span></li>
										</ul>
									</div>
								</div>
								<div class="itemSize active">
									<div class="numberSize">38</div>
									<div class="lengthSole"><span class="valLength">240</span> мм</div>
									<div class="availability">в наличии</div>
									<div class="boxCounter">
										<div class="titleCounter">Количество</div>
										<ul class="counterProd">
											<li class="countBtn prevCount"><span class="val"></span></li>
											<li class="numberCount"><span class="number">1</span> <span class="titleNumber">пара</span></li>
											<li class="countBtn nextCount"><span class="val"></span></li>
										</ul>
									</div>
								</div>
								<div class="itemSize">
									<div class="numberSize">39</div>
									<div class="lengthSole"><span class="valLength">240</span> мм</div>
									<div class="availability">в наличии</div>
									<div class="boxCounter">
										<div class="titleCounter">Количество</div>
										<ul class="counterProd">
											<li class="countBtn prevCount"><span class="val"></span></li>
											<li class="numberCount"><span class="number">1</span> <span class="titleNumber">пара</span></li>
											<li class="countBtn nextCount"><span class="val"></span></li>
										</ul>
									</div>
								</div>
								<div class="itemSize">
									<div class="numberSize">40</div>
									<div class="lengthSole"><span class="valLength">240</span> мм</div>
									<div class="availability">в наличии</div>
									<div class="boxCounter">
										<div class="titleCounter">Количество</div>
										<ul class="counterProd">
											<li class="countBtn prevCount"><span class="val"></span></li>
											<li class="numberCount"><span class="number">1</span> <span class="titleNumber">пара</span></li>
											<li class="countBtn nextCount"><span class="val"></span></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="btns">
								<div class="boxBtn">
									<button class="autorizationBtn cancelBtn visibleBtn1" type="submit">Отмена</button>
								</div>
								<div class="boxBtn">
									<button class="autorizationBtn addBtn visibleBtn1" type="submit">Добавить</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<main class="content pageMain"><?= $content ?></main>
	</div>
</body>
</html>
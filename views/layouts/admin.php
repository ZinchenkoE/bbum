<?php
use yii\helpers\Html;
/** @var $content */
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>

		<link rel="stylesheet" href="http://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="/css/jquery.Jcrop.min.css">
        <link rel="stylesheet" href="/css/admin.css">

        <script src="/js/lib/jquery-3.1.1.min.js"></script>
        <script src="/js/lib/jquery.Jcrop.min.js"></script>
        <script src="/js/lib/ckeditor/ckeditor.js"></script>
        <script src="/js/lib/maskedInput.js"></script>
        <script src="/js/a_admin.js"></script>
        <script src="/js/a.Query.js"></script>
        <script src="/js/a.InputFile.js"></script>
        <script src="/js/a.Validator.js"></script>
		<script src="/js/a.Select.js"></script>
		<script src="/js/devTools.js"></script>
    </head>
    <body>
        <?php if(!Yii::$app->user->isGuest): ?>
        <header>
            <ul>
                <li><span class="count" style="display: none;"></span><i class="icon icon-phone"></i></li>
                <li><span class="count" style="display: none;"></span><i class="icon icon-notification"></i></li>
                <li><span class="count" style="display: none;"></span><i class="icon icon-mail"></i></li>
                <li><a href="<?= Yii::$app->request->hostInfo ?>" target="_blank"><i class="icon icon-home"></i><span>Ваш сайт</span></a></li>
            </ul>
            <div class="textField inputBox search">
                <input placeholder="Найти ..." id="search" value="<?= Yii::$app->request->get('search') ?? '' ?>"
                       pattern="text" class="initialized">
            </div>
        </header>
        <nav>
            <div class="logo">
                <a href="/admin"><img src="/img/admin/logo.png" style="height: 85px;"></a>
            </div>
            <ul>
                <li><a href="/admin/"><i class="icon material-icons">shopping_cart</i><span>Заказы</span></a></li>
                <li><a href="/admin/products-table"><i class="icon icon-product"></i><span>Товары</span></a></li>
                <li><a href="/admin/categories-table"><i class="icon icon-page"></i><span>Категории</span></a></li>
            </ul>
            <div class="userMenuBox">
                <div class="js-openUserMenuBtn openUserMenuBtn"><i class="icon icon-user"></i></div>
                <div class="userMenu js-userMenu">
                    <p class="js-logout">Выход</p>
                    <i class="icon icon-cross"></i>
                </div>
            </div>
        </nav>
        <?php endif; ?>
        <main><?= $content ?></main>

        <?php if(Yii::$app->session->hasFlash('error')): ?>
            <span class="flashError" hidden><?=Yii::$app->session->getFlash('error'); ?></span>
        <?php endif; ?>
        <script>a.init();</script>
    </body>
</html>

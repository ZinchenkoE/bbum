<div class="LoginPage">
    <form class="formLogin" action="/admin/login" method="post">
        <input type="hidden" name="_prm" value="login">
        <h1>Вход</h1>
        <div class="textField inputBox">
            <input placeholder="Логин" class="initialized" id="login" name="email" type="text" pattern="email" required
                   value="<?= Yii::$app->request->getUserIP() == '127.0.0.1' ? 'admin@ww.ww' : '' ?>">
        </div>
        <div class="textField inputBox">
            <input placeholder="Пароль" class="initialized" id="password" name="password" type="password" required
                   value="<?= Yii::$app->request->getUserIP() == '127.0.0.1' ? 'admin@ww.ww' : '' ?>">
        </div>
        <a href="#"></a>
        <div class="submitBox">
            <button type="submit"><span>Войти</span></button>
        </div>
    </form>
</div>

<div class="logIn boxAutorization">
    <div class="loginBox">
        <h2 class="title">Авторизация</h2>
        <form action="" class="formLogIn formAutorization">
            <div class="boxForm">
                <div class="fields">
                    <div class="boxField" required>
                        <input type="text" id="loginUser" class="input__field" />
                        <label for="loginUser" class="input__label">
                            <span class="labelContent">Логин или e-mail</span>
                        </label>
                    </div>
                    <div class="boxField" required>
                        <input type="password" id="loginPass" class="input__field"/>
                        <label for="loginPass" class="input__label">
                            <span class="labelContent">Пароль</span>
                            <span class="forgotPassword"><a href="#">Забыли пароль?</a></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="btns">
                <div class="boxBtn">
                    <button class="autorizationBtn regBtn visibleBtn2" type="button" onclick="window.location = 'registration.html'">Зарегистрироваться</button>
                </div>
                <div class="boxBtn">
                    <button class="autorizationBtn loginBtn visibleBtn1" type="submit">Войти</button>
                </div>
            </div>
        </form>
    </div>
    <div class="forgotLogin">
        <h2 class="title">Восстановление пароля</h2>
        <form action="" class="formLogIn formAutorization">
            <div class="boxForm">
                <div class="fields">
                    <div class="boxField" required>
                        <input type="email" id="email" class="input__field" />
                        <label for="email" class="input__label">
                            <span class="labelContent">E-mail</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="btns">
                <div class="boxBtn">
                    <button class="autorizationBtn forgotPassBtn visibleBtn1" type="submit">Отправить запрос</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>$('main').addClass('pageAutorization');</script>

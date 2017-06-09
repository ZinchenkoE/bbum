<div class="logIn boxRegistration">
    <h2 class="title">Регистрация</h2>
    <form action="" class="formLogIn formRegistration" autocomplete="off">
        <div class="boxForm">
            <div class="boxUserData">
                <h3 class="titleData">Личные данные</h3>
                <div class="fields">
                    <div class="col">
                        <div class="boxField" required>
                            <input type="text" id="name" class="input__field" />
                            <label for="name" class="input__label">
                                <span class="labelContent">Имя</span>
                            </label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="boxField" required>
                            <input type="text" id="surename" class="input__field" />
                            <label for="surename" class="input__label">
                                <span class="labelContent">Фамилия</span>
                            </label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="boxField" required>
                            <input type="text" id="city" class="input__field" />
                            <label for="city" class="input__label">
                                <span class="labelContent">Город</span>
                            </label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="boxField" required>
                            <input type="tel" id="userPhone" class="input__field" />
                            <label for="userPhone" class="input__label">
                                <span class="labelContent">Телефон</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="boxUserData">
                <h3 class="titleData">Данные доступа</h3>
                <div class="fields">
                    <div class="col">
                        <div class="boxField" required>
                            <input type="text" id="login" class="input__field" />
                            <label for="login" class="input__label">
                                <span class="labelContent">Логин</span>
                            </label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="boxField" required>
                            <input type="email" id="email" class="input__field" />
                            <label for="email" class="input__label">
                                <span class="labelContent">E-mail</span>
                            </label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="boxField" required>
                            <input name="password" type="password" id="pass" class="input__field" maxlength="20"/>
                            <label for="pass" class="input__label">
                                <span class="indicator"></span>
                                <span class="labelContent">Пароль</span>
                            </label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="boxField" required>
                            <input type="password" id="confirmPass" class="input__field"/>
                            <label for="confirmPass" class="input__label">
                                <span class="labelContent">Повторите пароль</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="boxChechbox">
                <input type="checkbox" id="check" checked="checked">
                <label for="check">Получать специальные предложения и новости на e-mail</label>
            </div>
        </div>
        <div class="btns">
            <div class="boxBtn">
                <button class="autorizationBtn loginBtn visibleBtn2" type="button">Войти</button>
            </div>
            <div class="boxBtn">
                <button class="autorizationBtn  visibleBtn1 validBtn" type="submit">Зарегистрироваться</button>
            </div>
        </div>
    </form>
</div>
<script>$('main').addClass('pageRegistration');</script>
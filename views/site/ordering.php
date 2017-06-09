<div class="logIn boxOrdering">
    <h2 class="title">Оформление заказа</h2>
    <form action="" class="formLogIn formAutorization" autocomplete="off">
        <div class="boxForm">
            <div class="fields">
                <div class="boxField" required>
                    <input type="text" id="name" class="input__field">
                    <label for="name" class="input__label">
                        <span class="labelContent">Имя и Фамилия</span>
                    </label>
                </div>
                <div class="boxField" required>
                    <input type="tel" id="userPhone" class="input__field">
                    <label for="userPhone" class="input__label">
                        <span class="labelContent">Телефон</span>
                    </label>
                </div>
                <div class="boxField" required>
                    <input type="email" id="email" class="input__field">
                    <label for="email" class="input__label">
                        <span class="labelContent">E-mail</span>
                    </label>
                </div>
                <div class="boxField" required>
                    <input type="text" id="adress" class="input__field">
                    <label for="adress" class="input__label">
                        <span class="labelContent">Адрес доставки</span>
                    </label>
                </div>
                <div class="boxField" required>
                    <select class="cs-select cs-skin-underline" name="Способ оплаты">
                        <option value="" disabled selected></option>
                        <option value="1">Наличные</option>
                        <option value="2">Безнал</option>
                        <option value="3">Карта</option>
                        <option value="3">Наложенный платеж</option>
                    </select>
                </div>
                <div class="boxField" required>
                    <select class="cs-select cs-skin-underline" name="Способ доставки">
                        <option value="" disabled selected></option>
                        <option value="1">Самовывоз</option>
                        <option value="2">Курьерская доставка</option>
                        <option value="3">Отпрака ТК</option>
                    </select>
                </div>
                <div class="boxField" required>
                    <input type="text" id="coment" class="input__field">
                    <label for="coment" class="input__label">
                        <span class="labelContent">Комментарий</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="btns">
            <div class="boxBtn">
                <button class="autorizationBtn loginBtn visibleBtn1 validBtn" type="submit">Отправить</button>
            </div>
        </div>
    </form>
</div>

<script>$('main').addClass('pageOrdering');</script>
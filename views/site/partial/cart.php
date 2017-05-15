<div id="Cart" data-objs="Cart">
    <form action="/order" method="create">
		<h2>Корзина</h2>
        <ul class="products"></ul>
        <div class="totalOrderPriceBox">
			<div class="label">Всего:</div>
			<div class="totalOrderPrice"><span></span> грн.</div>
        </div>
        <div class="orderInfo">
			<input type="hidden" id="Cart-customer_id" name="customer_id" value="0">
            <div class="textField inputBox">
                <input type="text" id="Cart-customer_name" name="customer_name" value=""
					   placeholder="Имя Фамилия" pattern="text" required>
            </div>
            <div class="textField inputBox">
                <input type="text" id="Cart-email" name="email" value=""
					   placeholder="E-mail" pattern="email" required>
            </div>
            <div class="textField inputBox">
                <input type="text" id="Cart-phone" name="phone" value=""
					   placeholder="Телефон" data-mask="+38 (099) 999 99 99" required>
            </div>
            <div class="selectField inputBox">
                <label class="title">Служба доставки</label>
                <select id="Cart-delivery_id" name="delivery_id" required>
                    <option value="1" selected>Новая почта</option>
                    <option value="2">ИнТайм</option>
                    <option value="3">Деливери</option>
                    <option value="4">Укр. Почта</option>
                </select>
            </div>
			<div class="selectField inputBox">
                <label class="title">Город</label>
                <?= Yii::$app->controller->renderPartial('/site/partial/cart-city_select'); ?>
            </div>
            <div class="selectField inputBox">
                <label class="title">Отделение</label>
                <?= Yii::$app->controller->renderPartial('/site/partial/cart-stock_select'); ?>
            </div>
        </div>
        <div class="btnGroup">
            <button id="Cart-closeCart" class="grayBtn"  type="button">продолжить покупки</button>
            <button id="Cart-sendOrder" class="blackBtn" type="submit"
					data-before-submit-func="Cart.beforeSubmit"
					data-success-submit-func="Cart.successSubmit">оформить заказ</button>
        </div>
    </form>
    <script>
        var Cart = {
            order: [
                // { product_id, title_ru, title_uk, image, price, quantity}
            ],
			orderInfo: {
                'Cart-customer_name': '',
                'Cart-email': 		  '',
                'Cart-phone': 		  '',
                'Cart-delivery_id':   1,
                'Cart-citySelect': 	  '',
			},
            handlers: {
                "#Cart-customer_name, #Cart-email, #Cart-phone, #Cart-delivery_id, #Cart-citySelect:change": function() { Cart.saveOrderInfo(); },
                "#Cart .cartAmount .plus:click" : function () { Cart.changeQuantity(this, true);},
                "#Cart .cartAmount .minus:click": function () { Cart.changeQuantity(this, false); },
                "#Cart-closeCart:click"         : function () { Cart.hide();},
                "#Cart-citySelect:change"       : function () { Cart.renderStockField($(this));},
                "#Cart .delProduct:click"      	: function () { Cart.delProductClick($(this));},
            },
            ready: function () {
                if (localStorage.order)     Cart.order     = JSON.parse(localStorage.order);
                if (localStorage.orderInfo) Cart.orderInfo = JSON.parse(localStorage.orderInfo);
                if (!(Cart.order instanceof Array)) {
                    Cart.order = [];
                    localStorage.order = JSON.stringify(Cart.order);
                }
                if (!(Cart.orderInfo instanceof Object)) {
                    Cart.orderInfo = {};
                    localStorage.orderInfo = JSON.stringify(Cart.order);
                }
                $('#Cart-customer_name').val(Cart.orderInfo['Cart-customer_name']);
                $('#Cart-email').val(Cart.orderInfo['Cart-email']);
                $('#Cart-phone').val(Cart.orderInfo['Cart-phone']);
                $('#Cart-delivery_id').setVal(Cart.orderInfo['Cart-delivery_id']);
                $('#Cart-citySelect').setVal(Cart.orderInfo['Cart-citySelect']);
            },
            show: function () {
                $('#Cart, #overlay').fadeIn(200);
                Cart.render();
            },
            hide: function () { $('#overlay, #Cart').fadeOut(200); },
            render: function () {
                var html = '';
                Cart.order.forEach(function (p) {
                    html += '<li class="product" data-product-id="' + p.product_id + '">' +
                            '	<input type="hidden" name="products[]" value="' + p.product_id + '">' +
                            '    <div class="cartAmount">' +
                            '        <div class="plus">+</div>' +
                            '        <input class="amount" type="text" name="quantity[]" value="' + p.quantity + '">' +
                            '        <div class="minus">-</div>' +
                            '    </div>' +
                            '	 <div class="title">' + p['title_' + a.params.lang] + '</div>' +
                            '	 <div class="price">' + p.price + ' грн</div>' +
                            '	 <div class="totalPrice">' + (p.price * p.quantity).toFixed(2) + ' грн</div>' +
                            '	 <div class="delProduct"><i class="material-icons">clear</i></div>' +
                            '</li>';
                });
                $('#Cart .products').html(html);
                Cart.calcTotalPrice();
            },
            calcTotalPrice: function () {
                var totalOrderPrice = 0;
                Cart.order.forEach(function (p) {
                    totalOrderPrice += p.price * p.quantity;
                });
                $('.totalOrderPrice span').text(totalOrderPrice);
                localStorage.order = JSON.stringify(Cart.order);
            },
            changeQuantity: function (el, actionPlus) {
                var pId = +$(el).closest('[data-product-id]').attr('data-product-id');
                var product = Cart.getProductById(pId);
                console.log(product);

                if (product.quantity <= 1 && !actionPlus) return; // Стопор чтоб не уходить в минус
                actionPlus ? product.quantity++ : product.quantity--;

                var totalPrice = product.quantity * product.price;

                $(el).siblings('input').val(product.quantity);
                $(el).parent().siblings('.totalPrice').text(totalPrice + ' грн.');
                Cart.calcTotalPrice();
            },
            getProductById: function (pId) {
                pId = +pId;
                console.log(pId);
                for (var i = 0; i < Cart.order.length; i++) {
                    if (Cart.order[i].product_id === pId) return Cart.order[i];
                }
                return null;
            },
            renderStockField: function ($citySelect) {
                $('#Cart-stock').parent().remove();
                if (+$('#Cart-delivery_id').val() === 1) {
                    var params = {
                        "modelName": "AddressGeneral",
                        "calledMethod": "getWarehouses",
                        "methodProperties": {
                            "CityRef": $('#Cart-citySelect').val()  // "db5c88e0-391c-11dd-90d9-001a92567626"
                        },
                        "apiKey": "fe6e03d4eecde92caf8c527979c861bf"
                    };
                    $.ajax({
                        url: 'https://api.novaposhta.ua/v2.0/json/?' + $.param(params),
                        type: 'POST',
                        dataType: 'jsonp',
                    }).done(function (res) {
                        var h = '<option value="" selected></option>';
                        res.data.forEach(function (item) {
                            h += '<option value="' + item.Ref + '">' + item.DescriptionRu + '</option>';
                        });
                        var field = '<div class="selectField inputBox">' +
                                    '    <label class="title">Отделение</label>' +
                                    '    <select id="Cart-stock" name="stock" class="searchSelect">' + h + '</select>' +
                                    '</div>';
                        $(field).insertAfter($citySelect.parent());
                        a.upgradeElements();
                    }).fail(function () {
                        console.error('__error__');
                    });
                } else {
                    $('<div class="textField inputBox">' +
                      '   <input type="text" id="Cart-stock" name="stock" placeholder="Номер склада" data-only-pattern="integer" required>' +
                      '</div>').insertAfter($citySelect.parent());
                }
            },
			saveOrderInfo: function() {
                Cart.orderInfo = {
                    'Cart-customer_name': 	$('#Cart-customer_name').val(),
					'Cart-email': 			$('#Cart-email').val(),
                    'Cart-phone': 			$('#Cart-phone').val(),
					'Cart-delivery_id': 	$('#Cart-delivery_id').val(),
                    'Cart-citySelect': 		$('#Cart-citySelect').val(),
				};
                localStorage.orderInfo = JSON.stringify(Cart.orderInfo);
			},
            beforeSubmit: function() {
                if(!Cart.order.length) {
                    a.MessageBox('N::Нет продуктов для оформления заказа!');
                    return false;
				}
                return true;
			},
            delProductClick: function($el) {
                var pId = +$el.closest('[data-product-id]').attr('data-product-id');
                console.log(pId);
                for (var i = 0; i < Cart.order.length; i++) {
                    if (Cart.order[i].product_id == pId){
                        Cart.order.splice(i, 1);
                        console.log('C заказа удалили продукт, pId - ' + pId);
                    }
                }
                Cart.render();
			},
            successSubmit: function () {
//                Cart.order = [];
//                localStorage.order = JSON.stringify(Cart.order);
//                Cart.hide();
            },
        };
    </script>
</div>
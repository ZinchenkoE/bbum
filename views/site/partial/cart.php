<div id="Cart" data-objs="Cart">
    <div class="firstStep">
		<h2>Корзина</h2>
        <ul class="products"></ul>
        <div class="totalOrderPriceBox">
			<div class="label">Всего:</div>
			<div class="totalOrderPrice"><span></span> грн.</div>
        </div>
        <div class="orderinfo">
            <div class="textField inputBox">
                <input type="text" id="customer_name" placeholder="Имя Фамилия получателя" pattern="text" required>
            </div>
            <div class="textField inputBox">
                <input type="text" id="customer_name" placeholder="Телефон" data-mask="+38 (099) 999 99 99">
            </div>
            <div class="selectField inputBox">
                <label class="title">Служба доставки</label>
                <select id="delivery_id">
                    <option value="0" selected></option>
                    <option value="1">Новая почта</option>
                    <option value="2">ИнТайм</option>
                    <option value="3">Деливери</option>
                    <option value="4">Укр. Почта</option>
                </select>
            </div>

        </div>
        <div class="btnGroup">
            <button class="grayBtn">продолжить покупки</button>
            <button class="blackBtn sendOrder">оформить заказ</button>
        </div>
    </div>
    <script>
        var Cart = {
            order: [
//				{ product_id, title_ru, title_uk, image, price, quantity}
			],
            handlers: {
                "#Cart .cartAmount .plus:click" : function() { Cart.changeQuantity(this, true);  },
                "#Cart .cartAmount .minus:click": function() { Cart.changeQuantity(this, false); },
                "#Cart .sendOrder:click"        : function() { Cart.sendOrder(); },
                "#Cart #delivery_id:change"     : function() { Cart.renderCitySelect($(this)); },
                "#Cart #citySelect:change"      : function() { Cart.renderStockSelect($(this)); },
            },
            ready: function(){
                if(localStorage.order) Cart.order = JSON.parse(localStorage.order);
            },
			show: function() {
                $('#Cart, #overlay').fadeIn(200);
                Cart.render();
			},
			render: function() {
                var html = '';
                Cart.order.forEach(function(p) {
                    html += '<li class="product" data-product-id="' + p.product_id + '">'+
                        	'    <div class="cartAmount">'+
                        	'        <div class="plus">+</div>'+
                        	'        <input class="amount" type="text" value="' + p.quantity + '">'+
                        	'        <div class="minus">-</div>'+
                        	'    </div>'+
                        	'	 <div class="title">' + p['title_' + a.params.lang] + '</div>'+
                        	'	 <div class="price">' + p.price + ' грн</div>'+
                        	'	 <div class="totalPrice">' + (p.price*p.quantity).toFixed(2) + ' грн</div>'+
                        	'</li>';
				});
                $('#Cart .products').html(html);
                Cart.calcTotalPrice();
			},
			calcTotalPrice: function() {
                var totalOrderPrice = 0;
                Cart.order.forEach(function(p) {
                    totalOrderPrice += p.price*p.quantity;
                });
                $('.totalOrderPrice span').text(totalOrderPrice);
                localStorage.order = JSON.stringify(Cart.order);
			},
            changeQuantity: function(el, actionPlus) {
                var pId = +$(el).closest('[data-product-id]').attr('data-product-id');
                var product = Cart.getProductById(pId);
                console.log(product);

                if(product.quantity <= 1 && !actionPlus) return; // Стопор чтоб не уходить в минус
                actionPlus ? product.quantity++ : product.quantity--;

                var totalPrice = product.quantity*product.price;

                $(el).siblings('input').val(product.quantity);
                $(el).parent().siblings('.totalPrice').text(totalPrice + ' грн.');
                Cart.calcTotalPrice();
			},
            getProductById: function(pId) {
                for (var i=0; i < Cart.order.length; i++) {
                    if (Cart.order[i].product_id === pId) return Cart.order[i];
                }
                return null;
            },
            sendOrder: function(){
                var fd = new FormData();
                fd.append('products', Cart.order);
                a.Query.create({
                    url: '/order',
                    data: fd
                });
            },
            renderCitySelect: function($deliverySelect){
                if($deliverySelect.hasClass('citiesReceived')) return;
                var params = {
                    "modelName": "Address",
                    "calledMethod": "getCities",
                    "apiKey": "fe6e03d4eecde92caf8c527979c861bf"
                };
                $.ajax({
                    url: 'https://api.novaposhta.ua/v2.0/json/?' + $.param(params),
                    type: 'POST',
                    dataType: 'jsonp',
                }).done(function (res) {
                    var h = '<option value="" selected></option>';
                    res.data.forEach(function(item) {
                        h += '<option value="' + item.Ref + '">' + item.DescriptionRu + '</option>';
                    });
                    var field = '<div class="selectField inputBox">'+
                                '    <label class="title">Город</label>'+
                                '    <select id="citySelect" class="searchSelect">' + h + '</select>'+
                                '</div>';
                    $(field).insertAfter($deliverySelect.parent());
                    a.upgradeElements();
                    $deliverySelect.addClass('citiesReceived');
                }).fail(function () {
                    console.error('__error__');
                });
            },
            renderStockSelect: function($citySelect){
                $('#stock').parent().remove();
                if($('#delivery_id').val() == 1 ){
                    var params = {
                        "modelName": "AddressGeneral",
                        "calledMethod": "getWarehouses",
                        "methodProperties": {
                            "CityRef": $('#citySelect').val()  // "db5c88e0-391c-11dd-90d9-001a92567626"
                        },
                        "apiKey": "fe6e03d4eecde92caf8c527979c861bf"
                    };
                    $.ajax({
                        url: 'https://api.novaposhta.ua/v2.0/json/?' + $.param(params),
                        type: 'POST',
                        dataType: 'jsonp',
                    }).done(function (res) {
                        var h = '<option value="" selected></option>';
                        res.data.forEach(function(item) {
                            h += '<option value="' + item.Ref + '">' + item.DescriptionRu + '</option>';
                        });
                        var field = '<div class="selectField inputBox">'+
                                    '    <label class="title">Отделение</label>'+
                                    '    <select id="stock" class="searchSelect">' + h + '</select>'+
                                    '</div>';
                        $(field).insertAfter($citySelect.parent());
                        a.upgradeElements();
                    }).fail(function () {
                        console.error('__error__');
                    });
                }else{
                    $('<div class="textField inputBox">'+
                      '   <input id="stock" placeholder="Номер склада" data-only-pattern="integer" required>'+
                      '</div>').insertAfter($citySelect.parent());
                }
            },

        };
    </script>
</div>
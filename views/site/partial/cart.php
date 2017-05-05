<div id="Cart" data-objs="Cart">
    <div class="firstStep">
		<h2>Корзина</h2>
        <ul class="products"></ul>
        <div class="totalOrderPriceBox">
			<div class="label">Всего:</div>
			<div class="totalOrderPrice"><span></span> грн.</div>
        </div>
        <div class="btnGroup">
            <button class="grayBtn">продолжить покупки</button>
            <button class="blackBtn">оформить заказ</button>
        </div>
    </div>
    <script>
        var Cart = {
            order: [
//				{
//                    product_id
//                    title_ru
//                    title_uk
//                    image
//                    price
//                    quantity
//				}
			],
            handlers: {
                "#Cart .cartAmount .plus:click" : function() { Cart.changeQuantity(this, true);  },
                "#Cart .cartAmount .minus:click": function() { Cart.changeQuantity(this, false); },
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
                    console.log(p.price, p.quantity);
                    totalOrderPrice += p.price*p.quantity;
                });
                $('.totalOrderPrice span').text(totalOrderPrice);
			},
            changeQuantity: function(el, actionPlus) {
                var pId = $(el).closest('[data-product-id]').attr('data-product-id');
                var product = Cart.getProductById(pId);

                if(product.quantity <= 1 && !actionPlus) return; // Стопор чтоб не уходить в минус
                actionPlus ? product.quantity++ : product.quantity--;

                var totalPrice = product.quantity*product.price;

                $(el).siblings('input').val(product.quantity);
                $(el).parent().siblings('.totalPrice').text(totalPrice + ' грн.');
                Cart.calcTotalPrice();
			},
            getProductById: function(pId) {
                for (var i=0; i < Cart.order.length; i++) {
                    if (Cart.order[i].product_id == pId) return Cart.order[i];
                }
                return null;
            },
        };
    </script>
</div>
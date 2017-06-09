<?
$lng = Yii::$app->lng->getLng();
$w   = Yii::$app->lng->getDictionary();
?>

<div id="ProductPage" data-objs="ProductPage">
    <div class="row">
        <div class="leftPart">
            <ul id="imageGallery">
                <? foreach ($data['images'] as $img): ?>
                    <li data-thumb="/<?= $img['src'] ?>" data-src="/<?= $img['src'] ?>">
                        <img src="/<?= $img['src'] ?>" />
                    </li>
                <? endforeach; ?>
            </ul>
        </div>
        <div class="rightPart">
            <p>Артикул: 100-<?= $data['product']['product_id'] ?></p>
            <p><?= $w['title'] ?>: <?= $data['product']['title_'.$lng] ?></p>
            <p><?= $w['description'] ?>: <?= $data['product']['description_'.$lng] ?></p>
            <p><?= $w['price'] ?>: <?= $data['product']['price'] ?> грн</p>
			<button class="blackBtn addProductToCart"><?= $w['bay'] ?></button>
        </div>
    </div>
	<script>
        var ProductPage = {
            product: <?= json_encode($data['product']) ?>,
            handlers: {
                "#ProductPage .addProductToCart:click" : function() { ProductPage.addProductToCart($(this));  },
                "#ProductPage .goToCart:click" 		   : function() { Cart.show(); },
            },
			ready: function() {
                var pId = ProductPage.product.product_id;
                if(Cart.getProductById(pId)){
                    $('.addProductToCart').removeClass('addProductToCart blackBtn')
						.addClass('goToCart grayBtn').text('Товар в корзине');
				}
                ProductPage.initLightSlider();
			},
            addProductToCart: function($el) {
                var pId = ProductPage.product.product_id;
                if(!Cart.getProductById(pId)){
                    var newProduct = {
                        product_id:	+pId,
                        title_ru:	ProductPage.product.title_ru,
                        title_uk:	ProductPage.product.title_uk,
                        image:		ProductPage.product.image,
                        price:		+ProductPage.product.price,
                        quantity:	1,
                    };
                    Cart.order.push(newProduct);
                    a.MessageBox('S::В корзину добавлен новый товар.');
                    $el.removeClass('addProductToCart blackBtn').addClass('goToCart grayBtn').text('Товар в корзине');
				}else{
                    a.MessageBox('N::Продукт уже находится в корзине')
				}

			},
            initLightSlider: function() {
                $('#imageGallery').lightSlider({
                    gallery:true,
                    item:1,
                    loop:true,
                    thumbItem:9,
                    slideMargin:0,
                    enableDrag: false,
                    currentPagerPosition:'left',
                    onSliderLoad: function(el) {
                        el.lightGallery({
                            selector: '#imageGallery .lslide'
                        });
                    }
                });
            },
        };
	</script>
</div>




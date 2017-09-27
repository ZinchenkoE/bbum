<?
/** @var array $data  */
use app\components\Lng;
$product = $data['product'];
?>

<div id="ProductPage" data-objs="ProductPage">
    <div class="row">
        <div class="leftPart">
            <ul id="imageGallery">
                <? foreach ($product->imgs as $img): ?>
                    <li data-thumb="/<?= $img ?>" data-src="/<?= $img ?>">
                        <img src="/<?= $img ?>" />
                    </li>
                <? endforeach; ?>
            </ul>
        </div>
        <div class="rightPart">
            <p>Артикул: 100-<?= $product->id ?></p>
            <p><?= Lng::t('Название') ?>: <?= $product->title_ru ?></p>
            <p><?= Lng::t('Описание') ?>: <?= $product->description_ru ?></p>
            <p><?= Lng::t('Цена') ?>: <?= $product->price ?> грн</p>
			<button class="blackBtn addProductToCart"><?= Lng::t('Купить') ?></button>
        </div>
    </div>
	<script>
        var ProductPage = {
            product: <?= json_encode($product->attributes) ?>,
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




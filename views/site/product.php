<div class="backToCatalog">
    <a href="#"><span class="backIcon"></span> <span class="backText">Женская обувь</span></a>
</div>
<div class="boxSlider">
    <div class="sliderProduct">
        <ul id="slider1-pager">
            <li><a href="#"><img src="./images/slider1_img1_thumb.png" alt=""></a></li>
            <li><a href="#"><img src="./images/slider1_img2_thumb.png" alt=""></a></li>
            <li><a href="#"><img src="./images/slider1_img3_thumb.png" alt=""></a></li>
        </ul>
        <ul class="rslides" id="slider1">
            <li><img src="./images/slider1_img1.png" alt=""></li>
            <li><img src="./images/slider1_img2.png" alt=""></li>
            <li><img src="./images/slider1_img3.png" alt=""></li>
        </ul>
    </div>
    <div class="sliderProduct2">
        <div id="slides2" class="scroll-img">
            <ul class="slider">
                <? foreach ([1,1,1,1] as $item): ?>
                    <li class="slide"><a href="#"><img src="./images/product111.png" alt=""></a></li>
                    <li class="slide"><a href="#"><img src="./images/product112.png" alt=""></a></li>
                    <li class="slide"><a href="#"><img src="./images/product113.png" alt=""></a></li>
                <? endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<div class="boxCharecteristics">
    <div class="titleCharacteristic">
        <h3 class="nameProduct">Классические утепленные сапоги</h3>
        <div class="colorPoduct">Хаки</div>
        <div class="priceProduct">555.00 <span>грн</span></div>
    </div>
    <div class="boxSize">
        <h3 class="titleSize">Выберите размер</h3>
        <div class="size">
            <div class="itemSize">
                <div class="numberSize">37</div>
            </div>
            <div class="itemSize active">
                <div class="numberSize">38</div>
            </div>
            <div class="itemSize">
                <div class="numberSize">39</div>
            </div>
            <div class="itemSize">
                <div class="numberSize">40</div>
            </div>
            <div class="itemSize">
                <div class="numberSize">41</div>
            </div>
            <div class="itemSize">
                <div class="numberSize">42</div>
            </div>
        </div>
        <div class="boxInfoSize">
            <div class="boxCounter">
                <div class="titleCounter">Количество</div>
                <ul class="counterProd">
                    <li class="countBtn prevCount"><span class="val"></span></li>
                    <li class="numberCount"><p class="number">1</p> <p class="titleNumber">пара</p></li>
                    <li class="countBtn nextCount"><span class="val"></span></li>
                </ul>
            </div>
            <div class="colRight">
                <div class="availability">Товар в наличии</div>
                <div class="lengthSole">Длина стельки - <span class="valLength">240</span> мм</div>
            </div>
        </div>
        <div class="btns">
            <div class="boxBtn">
                <button class="autorizationBtn addProductToBag visibleBtn1" type="submit">В корзину</button>
            </div>
        </div>
    </div>
    <div class="boxDetail">
        <div class="itemDetail">
            <h3 class="titleDetail">Гарантия качества</h3>
            <div class="moreDetail">
                Обмен товара можно произвести в течение двух недель с момента получения! Именно в этих случаях все расходы по обмену товара несет интернет-магазин. В соответствии с Законом Украины «О защите прав потребителей» товар принимается обратно в случае отсутствия видимых признаков его ношения и только при наличии на нём всех ценников, и прочих ярлыков в неиспользованном виде, а так же при наличии фабричной упаковки. Не допускается нанесение на упаковку надписей, адресов и прочего.
            </div>
        </div>
        <div class="itemDetail">
            <h3 class="titleDetail">Оплата и доставка</h3>
            <div class="moreDetail">
                Обмен товара можно произвести в течение двух недель с момента получения! Именно в этих случаях все расходы по обмену товара несет интернет-магазин. В соответствии с Законом Украины «О защите прав потребителей» товар принимается обратно в случае отсутствия видимых признаков его ношения и только при наличии на нём всех ценников, и прочих ярлыков в неиспользованном виде, а так же при наличии фабричной упаковки. Не допускается нанесение на упаковку надписей, адресов и прочего.
            </div>
        </div>
        <div class="itemDetail">
            <h3 class="titleDetail">Обмен и возврат</h3>
            <div class="moreDetail">
                Обмен товара можно произвести в течение двух недель с момента получения! Именно в этих случаях все расходы по обмену товара несет интернет-магазин. В соответствии с Законом Украины «О защите прав потребителей» товар принимается обратно в случае отсутствия видимых признаков его ношения и только при наличии на нём всех ценников, и прочих ярлыков в неиспользованном виде, а так же при наличии фабричной упаковки. Не допускается нанесение на упаковку надписей, адресов и прочего.
            </div>
        </div>
        <div class="itemDetail">
            <h3 class="titleDetail">Описание и уход</h3>
            <div class="moreDetail">
                Обмен товара можно произвести в течение двух недель с момента получения! Именно в этих случаях все расходы по обмену товара несет интернет-магазин. В соответствии с Законом Украины «О защите прав потребителей» товар принимается обратно в случае отсутствия видимых признаков его ношения и только при наличии на нём всех ценников, и прочих ярлыков в неиспользованном виде, а так же при наличии фабричной упаковки. Не допускается нанесение на упаковку надписей, адресов и прочего.
            </div>
        </div>
    </div>
</div>
<script>
    $('body').on('click', '#slider1', function () {
        var coordsSlide = $('.rslides1_on img')[0].getBoundingClientRect();
        $('body').css('position', 'relative').append($('<div class="zoomBox"></div>').append($(this).find('.rslides1_on img').clone().addClass('zoomamble')));
        var k = Math.floor(($('html').height()/$('.zoomamble').height()*1000))/1000;
        var k1 = Math.floor(($('html').width()/$('.zoomamble').width()*1000))/1000;
        $('.zoomBox').css({'overflow': 'hidden', 'position': 'fixed', 'left': coordsSlide.left, 'top': coordsSlide.top, 'textAlign': 'center', 'zIndex': 49, 'background': '#fff'});
        $('.zoomBox').animate({'top': 0, 'left': 0, 'width': '100vw', 'height': '100vh'},300);
        $('.zoomamble').css({'marginLeft': -$('.rightSitebar').outerWidth()});
    });

    $('body').on('click', '.rightSitebar, .zoomBox', function () {
        var coordsSlide = $('.rslides1_on img')[0].getBoundingClientRect();
        if ($('body').find('.zoomBox').length ) {

            $('.zoomBox').animate({
                'top': coordsSlide.top, 'left': coordsSlide.left, 'width': $('.rslides1_on img').width(), 'height': $('.rslides1_on img').height()
            }, 150, function() {
                $('.zoomBox').remove();
            });
        }
    });
</script>
<script src="./plugins/zoom/velocity.min.js"></script>
<script src="./plugins/zoom/enhance.js"></script>
<script>$('main').addClass('pageProduct');</script>
<div class="headerCatalog">
    <ul class="productMenu">
        <li class="categoryProduct activeCategory"><a href="#">Женская <span class="wordWrap">обувь</span></a></li>
        <li class="categoryProduct smallCategory"><a href="#">Подростковая <span class="wordWrap">обувь</span></a></li>
        <li class="filterBtn"><a href="#"></a></li>
        <li class="categoryProduct smallCategory"><a href="#">Детская <span class="wordWrap">обувь</span></a></li>
        <li class="categoryProduct"><a href="#">Обувь для рыбалки <span class="wordWrap">и охоты</span></a></li>
    </ul>
    <div class="boxFilters">
        <div class="boxSort">
            <h5 class="filterTitle">Сортировать:</h5>
            <div class="boxView">
                <div class="viewBtn manyView"></div>
                <div class="viewBtn singleView activeView"></div>
            </div>
        </div>
        <div class="categoryFilter">
            <h6 class="catFilterTitle">Тип</h6>
            <ul class="paramFilter">
                <li class="itemFilter activeItem">Cапоги на низком ходу</li>
                <li class="itemFilter">Cапоги на высоком каблуке</li>
                <li class="itemFilter">Полусапожки</li>
            </ul>
        </div>
        <div class="categoryFilter">
            <h6 class="catFilterTitle">Размер</h6>
            <ul class="paramFilter">
                <li class="itemFilter activeItem">37</li>
                <li class="itemFilter">38</li>
                <li class="itemFilter">39</li>
                <li class="itemFilter">40</li>
            </ul>
        </div>
        <div class="categoryFilter">
            <h6 class="catFilterTitle">Цена</h6>
            <ul class="paramFilter">
                <li class="itemFilter activeItem">200 - 500 грн</li>
                <li class="itemFilter">500 - 1000 грн</li>
                <li class="itemFilter">100-1500 грн</li>
            </ul>
        </div>
        <div class="btns">
            <div class="boxBtn">
                <button class="autorizationBtn applyBtn visibleBtn1" type="submit">Применить</button>
            </div>
        </div>
    </div>
</div>
<div class="catalogBanner">
    <div class="boxInfo">
        <h2 class="title">Женская обувь</h2>
        <p class="specificationProduct">При создании каждой коллекции мы ориентируемся на последние мировые тенденции модной индустрии, а также на потребности широкого круга наших покупателей – от юных модниц до стильных деловых женщин.</p>
    </div>
</div>
<div class="products">
    <? foreach ([1,1,1,1,1,1] as $item): ?>
        <a href="product.html" class="boxProduct">
            <div class="boxImgProduct">
                <img src="/images/product2.png" class="imageProduct" alt="Product photo"/>
            </div>
            <h3 class="nameProduct">Классические утепленные сапоги</h3>
            <div class="colorPoduct">Красные</div>
            <div class="priceProduct">
                555 <span>грн</span>
            </div>
        </a>
    <? endforeach; ?>
</div>

<script>
    $('.boxFilters').css('top', $('.productMenu').outerHeight());
    $('.categoryProduct').on('click', function () {
        $(this).siblings().removeClass('activeCategory');
        $(this).addClass('activeCategory');
    });
    $('.filterBtn').on('click', function () {
        $(this).toggleClass('activeFilter');
        if ($(this).hasClass('activeFilter')) { $('.boxFilters').show(); } else { $('.boxFilters').hide(); }
    });
    $('.boxView').on('click', function (e) {
        if ( $(window).width() < 960 ) {
            $(this).children().toggleClass('activeView');
            if ($(this).children('.activeView').hasClass('manyView')) {
                $(this).closest('.pageCatalog').find('.products').addClass('mobileManyViews');
            }
            else {
                $(this).closest('.pageCatalog').find('.products').removeClass('mobileManyViews');
            }
        } else {
            if ( $(e.target).hasClass('manyView') ) {
                $(this).closest('.pageCatalog').find('.products').addClass('mobileManyViews');
            } else {
                $(this).closest('.pageCatalog').find('.products').removeClass('mobileManyViews');
            }
        }
    });
    $('.catFilterTitle').on('click', function () {
        $(this).parent().toggleClass('activeFilterTitle');
    });
    $('.itemFilter').on('click', function () {
        $(this).siblings().removeClass('activeItem');
        $(this).addClass('activeItem');
    });
</script>
<script>$('main').addClass('pageCatalog');</script>
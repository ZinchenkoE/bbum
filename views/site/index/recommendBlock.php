<?php
/** @var array $data  */
use app\components\Lng;
$lng = Lng::getLng();

?>

<div class="recommend" data-objs="RecommendBlock">
    <h3><?= Lng::t('Рекомендованные товары') ?></h3>
    <div class="slider" data-last-page="3" data-position="0">
        <div class="prev sliderBtn"></div>
        <div class="sliderInner row">
            <? foreach ($data['product_recommend'] as $product):
//                echo '<pre>'; print_r($product->category->title); die;
                ?>
                <a href="/<?= $lng ?>/product/<?= $product->id?>"
                   class="item <?= $product->id % 2 == 0  ? 'new' : '' ?>">
                    <div class="imgBox" style="background: url(/<?= $product->imgs[0] ?>) center center / contain no-repeat;">
                        <div class="substrate">
                            <button class="blackBtn"><?= Lng::t('Купить') ?></button>
                        </div>
                    </div>
                    <h4 class="title"><?= $product->title ?></h4>
                    <h5 class="subTitle"><?= $product->category->title ?? 555 ?></h5>
                    <div class="price"><?= $product->price ?> грн</div>
                </a>
            <? endforeach; ?>
        </div>
        <div class="next sliderBtn"></div>
    </div>
    <script>
        var RecommendBlock = {
            handlers: {
                ".sliderBtn.next:click": function() { RecommendBlock.sliderBtnNextClick(this); },
                ".sliderBtn.prev:click": function() { RecommendBlock.sliderBtnPrevClick(this); },
            },
            ready: function() {},
            sliderBtnNextClick: function(el) {
                var slider   = $(el).closest('.slider');
                var position = +slider.attr('data-position');
                var lastPage = +slider.attr('data-last-page');
                slider.attr('data-position', position + 1200);
                $('.sliderInner').css('transform', 'translateX(-' + (position + 1200) + 'px)');
                if( (position + 1200) >= lastPage * 1200) $(el).hide();
                $('.sliderBtn.prev').show();
            },
            sliderBtnPrevClick: function(el) {
                var slider     = $(el).closest('.slider');
                var position   = +slider.attr('data-position');
                slider.attr('data-position', position - 1200);
                $('.sliderInner').css('transform', 'translateX(-' + (position - 1200) + 'px)');
                if( (position - 1200) <= 0) $(el).hide();
                $('.sliderBtn.next').show();
            },

        };
    </script>
</div>
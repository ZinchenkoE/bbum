<?
/** @var array $data **/
use app\components\Lng;
$lng        = Yii::$app->request->get('lng');
$price_from = (int)Yii::$app->request->get('price_from');
$price_to   = (int)Yii::$app->request->get('price_to');

//echo '<pre>'; var_dump($data); die;
?>
<div id="CategoryPage" data-objs="CategoryPage">
    <? if(true): ?>
		<div class="row" style="margin-bottom: 50px;">
			<input class="genderFilter" type="checkbox" id="for_unisex"
                <?= (bool)Yii::$app->request->get('for_unisex') ? 'checked' : '' ?>
			><label for="forUnisex"><?= Lng::t('Унисекс') ?></label>
			<input class="genderFilter" type="checkbox" id="for_boy"
                <?= (bool)Yii::$app->request->get('for_boy')    ? 'checked' : '' ?>
			><label for="forBoy"   ><?= Lng::t('Для мальчиков')  ?></label>
			<input class="genderFilter" type="checkbox" id="for_girl"
                <?= (bool)Yii::$app->request->get('for_girl')   ? 'checked' : '' ?>
			><label for="forGirl"  ><?= Lng::t('Для девочек') ?></label>
		</div>
    <? endif; ?>

	<div class="row" style="margin-bottom: 50px;">
		<div id="priceRange"
			 style="width: 500px; margin-bottom: 20px;"
			 data-min="<?= $data['min_price'] ?>"
			 data-max="<?= $data['max_price'] ?>"
			 data-value-min="<?= $price_from ? $price_from : 0 ?>"
			 data-value-max="<?= $price_to   ? $price_to   : $data['max_price'] ?>"
		></div>
		<p>
			<label for="amount">Укажите размах цен:</label>
			<input id="amount" style="border:0; color:#f6931f; background:#fff;font-weight:bold;" disabled>
		</p>
	</div>

	<div class="row">
        <? foreach ($data['products'] as $product): ?>
			<a href="/<?= $lng ?>/product/<?= $product->id ?>" class="productInCategory с-3"
               style="background: url(/<?= $product->imgs[0] ?>) center center / cover no-repeat;">
				<h2><?= $product->title_ru ?> <br> <?= Lng::t('Цена') ?> : <?= $product->price ?> грн </h2>
			</a>
        <? endforeach; ?>
	</div>

    <? if(isset($data['pages'])): ?>
        <?= \yii\widgets\LinkPager::widget([
            'pagination' => $data['pages'],
            'maxButtonCount' => 10,
            'lastPageLabel'  => false,
            'firstPageLabel' => false,
            'nextPageLabel'  => false,
            'prevPageLabel'  => false,
        ]);?>
    <?php endif; ?>
	<script>
		var CategoryPage = {
			handlers: {
                ".genderFilter:change": function() { CategoryPage.addGenderFilter(this); },
			},
			ready: function() {
                CategoryPage.initRange();
			},
            initRange: function() {
                var priceRange = $("#priceRange");
                priceRange.slider({
                    range: true,
                    min: +priceRange.attr('data-min'),
                    max: +priceRange.attr('data-max'),
                    values: [ +priceRange.attr('data-value-min'), +priceRange.attr('data-value-max') ],
                    slide: function( event, ui ) {
                        $("#amount").val( priceRange.slider( "values", 0 ) + " - " + priceRange.slider( "values", 1 ) + 'грн' );
                    },
                    stop: function(event, ui) {
                        a.Query.get({url: Url.setParam({
                            price_from : ui.values[0],
                            price_to   : ui.values[1]
                        }), writeHistory: true });
                    }
                });
                $("#amount").val( priceRange.slider( "values", 0 ) + " - " + priceRange.slider( "values", 1 ) + 'грн' );
            },
            addGenderFilter: function(el) {
                if(el.checked){
                    var p = {};
                    p[el.id] = 1;
                    a.Query.get({url: Url.setParam(p) || location.pathname, writeHistory: true });
                }else{
                    a.Query.get({url: Url.removeParam([el.id]) || location.pathname, writeHistory: true });
                }
            },
		};
	</script>
</div>


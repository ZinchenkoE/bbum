<div id="Index" data-objs="Index" style="padding: 50px;">
	<h1 class="pageTitle">Товары</h1>
	<table>
		<thead>
		<tr>
			<th class="orderId"	    >№ ЗАКАЗА</th>
			<th class="customerName">КЛИЕНТ</th>
			<th class="email"		>E-MAIL</th>
			<th class="phone"		>ТЕЛЕФОН</th>
			<th class="city"		>ГОРОД</th>
			<th class="stock"		>СКЛАД</th>
			<th class="status"		>СТАТУС</th>
			<th class="btns"		>ПРОСМОТР</th>
		</tr>
		</thead>
		<tbody>
        <?php foreach([1,1,1,] as $order): ?>
			<tr order-id="1">
				<td class="orderId"	    >1</td>
				<td class="customerName">Женька</td>
				<td class="email"		>e-mail@sdf.ua</td>
				<td class="phone"		>38050505000</td>
				<td class="city"		>Город</td>
				<td class="stock"		>55</td>
				<td class="status"		>Новый</td>
				<td class="btns"		><i class="material-icons">format_align_justify</i></td>
			</tr>
        <?php endforeach; ?>
		</tbody>
	</table>
    <?php if(isset($data['pages'])): ?>
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
        var Index = {
            handlers: {
                "#qq:click": function(){},
            },
            ready: function () {}
        };
    </script>
</div>

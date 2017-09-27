<?
/** @var array $data  */
?>

<div id="Index" data-objs="Index">
	<h1 class="pageTitle">Заказы</h1>
	<table>
		<thead>
		<tr>
			<th class="orderId"	     >№ЗАКАЗА</th>
			<th class="customer_name">КЛИЕНТ</th>
			<th class="email"		 >E-MAIL</th>
			<th class="phone"		 >ТЕЛЕФОН</th>
			<th class="city"		 >ГОРОД</th>
			<th class="stock"		 >СКЛАД</th>
			<th class="total_price"	 >СУММА</th>
			<th class="status"		 >СТАТУС</th>
			<th class="btns"		 >ПРОСМОТР</th>
		</tr>
		</thead>
		<tbody>
        <?php foreach($data['orders'] as $order): ?>
			<tr order-id="<?= $order->id ?>">
				<td class="orderId"	     ><?= $order->id       ?></td>
				<td class="customer_name"><?= $order->customer_name  ?></td>
				<td class="email"		 ><?= $order->email 		 ?></td>
				<td class="phone"		 ><?= $order->phone 		 ?></td>
				<td class="city"		 ><?= $order->city_name 	 ?></td>
				<td class="stock"		 ><?= $order->stock 		 ?></td>
				<td class="total_price"	 ><?= $order->total_price    ?></td>
				<td class="status"		 >
					<?
							if($order->status === 0) echo 'новый'      ;
						elseif($order->status === 1) echo 'принятый'   ;
						elseif($order->status === 2) echo 'завершенный';
					?>
				</td>
				<td class="btns"><i class="material-icons"
                                    href="/admin/order/<?= $order->id ?>">format_align_justify</i></td>
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

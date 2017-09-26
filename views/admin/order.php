<?php
/** @var array $data  */
?>
<div class="formPage" data-objs="Order" id="Order">
    <form action="/admin/order/<?= $data['order']['order_id'] ?>" method="put">
        <h1 class="pageTitle">Заказ № <?= $data['order']['order_id'] ?></h1>
        <div class="leftCol">
            <div class="textField inputBox"><input placeholder="Клиент"       value="<?= $data['order']['customer_name'] ?>" disabled></div>
            <div class="textField inputBox"><input placeholder="E-mail"       value="<?= $data['order']['email'] ?>"         disabled></div>
			<div class="textField inputBox"><input placeholder="Телефон"      value="<?= $data['order']['phone'] ?>"         disabled></div>
			<div class="textField inputBox"><input placeholder="Город"        value="<?= $data['order']['city_name'] ?>"     disabled></div>
			<div class="textField inputBox"><input placeholder="Склад"        value="<?= $data['order']['stock'] ?>"         disabled></div>
			<div class="textField inputBox"><input placeholder="Сумма заказа" value="<?= $data['order']['total_price'] ?>"   disabled></div>
        </div>
        <div class="rightCol">
			<table>
				<thead>
				<tr>
					<th class="product_name"	>ТОВАР</th>
					<th class="product_price"   >ЦЕНА</th>
					<th class="product_quantity">КОЛИЧЕСТВО</th>
				</thead>
				<tbody>
				<?php foreach ($data['order_products'] ?? [] as $item): ?>
					<tr>
						<td class="product_name"	><?= $item['title_ru'] ?></td>
						<td class="product_price"	><?= $item['price']    ?></td>
						<td class="product_quantity"><?= $item['quantity']    ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
        </div>
        <button class="fixedSubmit" type="submit">ОК</button>
    </form>
	<script>
		var Order = {
		    handlers: {
		        "#Order .qq:click": function() {},
			}
		};
	</script>
</div>
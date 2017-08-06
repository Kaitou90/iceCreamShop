<?php
/**
*	Example of how to use PHP classes.
*
*/
require_once('order.php');

/**
* type = ice cream, milkshake or floats
*
* if float:
* special =  coca-cola, fanta, sprite, root beer, dr. pepper
*
* if milkshake
* special = skin milk, whole milk, 2% milk
*
* if ice cream
* special = waffle cone, cup, cake cone, sugar cone
*
* select is amount of scoops of ice cream and selected flavor
* Vanila, Strawberry, Chocolate, Mint chocolate, Cottoncandy
*/

$post = array(
	0 => array(
		'type' => 'ice cream',
		'special' => 'cup',
		'select' => array(
			0 => array(
			'flavor' => 'vanila',
			'amount' => 1
			)
		)
	),
	1 => array(
		'type' => 'milkshake',
		'special' => 'whole milk',
		'select' => array(
			0 => array(
			'flavor' => 'vanila',
			'amount' => 1
			)
		)
	),
	2 => array(
		'type' => 'floats',
		'special' => 'fanta',
		'select' => array(
			0 => array(
			'flavor' => 'mint chocolate',
			'amount' => 2
			),
			1 => array(
			'flavor' => 'strawberry',
			'amount' => 1
			),
			2 => array(
			'flavor' => 'vanila',
			'amount' => 5
			)
		)
	)
);

print_r('<pre>');
$item = new IceCream();
$order = new Order();

foreach ($post as $key => $product) {

	$item->setIceCreamType($product['type']);
	$item->setScoopsOf($product['select']);
	$item->setSelection($product['special']);

	$order->setItem($item);

	$item = new IceCream();

}

foreach ($order->items as $key => $value) {
	print_r("\r\n");
	print_r("Ordered ". $value->iceCreamType ." in ". $value->optionSelected ."\r\n");
	foreach ($value->scoopsOf as $scoop) {
		print_r($scoop['flavor'] ." x". $scoop['amount'] ."\r\n");
	}
}

print_r("\r\n");
print_r("Total");
print_r("\r\n");
print_r("Subtotal: $". $order->total['subTotal']);
print_r("\r\n");
print_r("Discount: - $". $order->total['discount']);
print_r("\r\n");
print_r("Total: $". $order->total['total']);
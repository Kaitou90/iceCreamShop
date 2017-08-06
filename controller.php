<?php

require_once('order.php');

$order = new Order();

$milks = (object) array('skin milk', 'whole milk', '2% milk');
$sodas = (object) array('coca-cola', 'fanta', 'sprite', 'root beer', 'dr. pepper');
$cones = (object) array('waffle cone', 'cup', 'cake cone', 'sugar cone');

$iceCreams = (object) array(
	'Ice cream cone' => array($cones, 'scoopsMax' => 2 )
	, 'milkshake' => array($milks, 'scoopsMax' => 1 )
	, 'floats' => array($sodas, 'scoopsMax' => null )
);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
		print_r($_POST);

		if ($_POST == 'type') {
			print_r("type");
			$order->setIceCream($_POST['type']);
			return;
		}
		if ($_POST == 'flavor') {
			print_r("flavor");
			print_r($_POST);
			return;
		}
	}

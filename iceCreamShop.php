<?php



$milks = array('skin milk', 'whole milk', '2% milk');
$sodas = array('coca-cola', 'fanta', 'sprite', 'root beer', 'dr. pepper');
$cones = array('waffle cone', 'cup', 'cake cone', 'sugar cone');

$iceCreams = (object) array(
	'Ice cream cone' => [$cones, 'scoopsMax' => 2]
	, 'milkshake' => [$milks, 'scoopsMax' => 1 ]
	, 'floats' => [$sodas, 'scoopsMax' => null ]
);

$flavors = (object) array('Vanila', 'Strawberry', 'Chocolate', 'Mint chocolate', 'Cottoncandy');

// $order = (object) array('type' => null, 'scoops' => array('flavor' => null, 'amount' => null), 'price' => null );


?>

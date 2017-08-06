<?php
	require_once('iceCreamShop.php');
	$order = new Order();

	print_r($_SERVER["REQUEST_METHOD"] == "POST");
	print_r($_SERVER);
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
			print_r("post method");
			print_r($_POST);
			print_r("<br />");

		if ( !empty( $_POST['type']) ) {
			print_r("type set");
			print_r($order);
			$order->setType($_POST['type']);
		}

		if ( !empty($_POST['amount'])) {

			$order->setScoops($_POST['flavor'], $_POST['amount']);
		}
	}


?>
<!DOCTYPE>
<html>
<head>
	<title></title>
</head>
<body>
hello <br />
What ice cream you would like? <br />
We have <br />
<?php

	foreach ($iceCreams as $key => $value) {
		echo $key . ", ";
	}
?>
<form name = "submitType" action = "<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method = "POST">
 Name: <input type = "text" name ="type" value="" />
 <!-- Age: <input type = "text" name = "age" /> -->
 <input type = "submit" value = "submitType" />
</form>

	<?php

    print_r('<br />');

	echo "What flavor you would like? <br />";
	echo "We have <br />";

	foreach ($flavors as $value) {
		echo $value . ", ";
	}
    ?>

	<form name = "submitAmount" action = "<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method = "POST">
	 amount: <input type = "text" name = "amount" value=""/>
	 flavor: <input type = "text" name = "flavor" value=""/>
	 <!-- Age: <input type = "text" name = "age" /> -->
	 <input type = "submit" value = "submitAmount" />
	</form>

	<?php

	print_r($order);
	?>
</body>
</html>

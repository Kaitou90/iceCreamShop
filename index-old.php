<?php require_once('iceCream.php') ?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
hello <br />
What ice cream you would like? <br />
We have <br />
<?php
$ice = new Order();
// order($iceCreams);
print_r($ice->getIceCreamOptions()) ?>

      <form action = "<?php $_PHP_SELF ?>" method = "POST">
         Name: <input type = "text" name ="type" value="<?php echo ((null !== $ice->getType())?$ice->getType():''); ?>" />
         <!-- Age: <input type = "text" name = "age" /> -->
         <input type = "submit" />
      </form>

      <?php

      if ( isset( $_POST['type']) ) {
      		$ice->setType($_POST['type']);
      }

      echo ((null !== $ice->getType())?$ice->getType():'ice cream type not selected');
      print_r('<br />');

      if ( null !== $ice->getType() ) {
      	print_r($ice->getScoopsLimit());
		echo "What flavor you would like? <br />";
		echo "We have <br />";

      	print_r($ice->getFlavorOptions());
      ?>

      <form action = "<?php $_PHP_SELF ?>" method = "POST">
         Name: <input type = "text" name = "flavor" value="<?php echo (isset($_POST['flavor'])?$_POST['flavor']:''); ?>"/>
         <!-- Age: <input type = "text" name = "age" /> -->
         <input type = "submit" />
      </form>
      <?php }?>
</body>
</html>


<?php
	print_r($_POST);
	if ( isset( $_POST['name']) ) {
	if ( $_POST['name']) {
		$ice->getScoopsLimit();
		// echo "What flavor you would like? <br />";
		// echo "We have <br />";
		// order($flavors);

		// echo $_POST['name'];

		switch ( trim(strtolower($_POST['name'])) ) {
			case 'ice cream':
				echo "Would you like one or two scoops? <br />";
				break;
			case 'milkshake':
				echo "To what milk you wish your shake to be made? <br />";
				order($milks);
				# code...
				break;
			case 'floats':
				echo "How many scoops?";

				// if($scoops == 1) {

				// } else {
				// echo "";
				// }
				echo "What soda you want your float to be made on?";
				order($sodas);
				# code...
				break;
			default:
				echo "I'm sorry. Could you repeat?";
				break;
		}
	}
	}
   // if( $_POST["name"] || $_POST["age"] ) {
   //    if (preg_match("/[^A-Za-z'-]/",$_POST['name'] )) {
   //       die ("invalid name and name should be alpha");
   //    }
   //    echo "Welcome ". $_POST['name']. "<br />";
   //    echo "You are ". $_POST['age']. " years old.";

   //    exit();
   // }
?>

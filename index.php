<?php
	require_once('order.php');
	session_start();

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		if(count($_SESSION['item']) > 0) {

			$amountIsInt = true;
			$tooMuch = false;

			$_SESSION['item']->setIceCreamType($_POST['type']);

			// Lets make sure that amount is integer.
			foreach ($_POST['select'] as $value) {
				if (!ctype_digit($value['amount'])) {
					$amountIsInt = false;
				}

				if ($_SESSION['item']->scoopsLeft < $value['amount'] && $_SESSION['item']->scoopsLeft !== 'infinite') {
					$tooMuch = true;
				}
			}

			// Stores ordered ice cream informations if there is no problems with the inputs.
			if ($amountIsInt && !$tooMuch) {
				$_SESSION['item']->setScoopsOf($_POST['select']);
				$_SESSION['item']->setSelection($_POST['special']);
				$_SESSION['order']->setItem($_SESSION['item']);

				$_SESSION['item'] = new IceCream();

			} else {
				print_r("Item not updated into order. Check the amount field. <br />");
				print_r("Maximum amount of scoops in ". $_POST['type'] ." is ". $_SESSION['item']->scoopsLeft. ". <br />");
			}
		} else {
			print_r("Cannot submit an empty item.");
		}
	}
	if (!isset($_SESSION['order'])) {
		$_SESSION['order'] = new Order();
		$_SESSION['item'] = new IceCream();
	}
?>

<!DOCTYPE>
<html>
<head>
	<title>Ice Cream Shop</title>
	<script type="text/javascript">
		var count = 0;

		window.onload = function() {
			<?php $list = new IceCream(); ?>

			// JSON formated list of ice cream options
			items = <?php echo $list->getIceCreams(1); ?>;
			console.log(items);

			// Inputs Ice cream options into dropdown.
			var type = document.getElementById("type");
			for (var i = 0; i < items.length; i++) {
				var option = document.createElement("option");

				option.value = items[i]['name'];
				option.textContent = items[i]['name'];
				type.appendChild(option);
			}

			// Prints Ice cream flavor options to user.
			flavors = <?php echo $list->getFlavors(1); ?>;
			for (var i = 0; i < flavors.length; i++) {
				document.getElementById("flavors").innerHTML += "<li>" + flavors[i] + "</li>";
			}

			selectFlavor();

			// Prints ordered Ice cream items into list element for the checkout.
			order = <?php echo json_encode($_SESSION['order']->getItems()); ?>;
			for (var key in order) {
				flav = '';
				for (var scoop in order[key]['scoopsOf']) {
					flav += "<li>"+order[key]['scoopsOf'][scoop]['flavor'] +" x "+order[key]['scoopsOf'][scoop]['amount']+"</li>";
				}

				str = "<b>Item:</b> "+ order[key]['iceCreamType'] + "<ul><li>"+order[key]['optionSelected']+"</li>"+flav+"<li> $"+order[key]['price']+"</li></ul>";
				document.getElementById("ordered").innerHTML += "<li>" + str + "</li>";
			}

			// Prints Receipt informations: subtotal, discount and total.
			total = <?php echo json_encode($_SESSION['order']->getTotal()); ?>;
			document.getElementById("ordered").innerHTML += "<li> <b>Total:</b> <ul><li>Sub total: $" + total['subTotal'] + "</li><li>Discount: - $"+ total['discount'] +"</li><li>Total: $"+total['total']+"</li></ul></li>";
		}

		// Updates ice cream options based on selected ice cream type.
		function updateSecondDrop() {
			var iceCreamType = document.getElementById("type").value;
			console.log("selected " + iceCreamType);

			orderItem = items.map(function(e) {return e["name"]}).indexOf(iceCreamType);

			var options = items[orderItem]['options'];
			var special = document.getElementById("special");

			document.getElementById("special").innerHTML = "<option >-- select --</option>";

			for (var i = 0; i < options.length; i++) {
				var option = document.createElement("option");
				option.value = options[i];
				option.textContent = options[i];
				special.appendChild(option);
			}

			document.getElementById('scoopMax').innerHTML = "Maximum amount of scoops in " + iceCreamType + " is "+items[orderItem]['scoopsMaxLeft'];

		}

		// Updates ice cream flavor options to dropdown.
		function selectFlavor() {
			var selectFlavor = document.querySelector('select[id="flavorSelect['+count+']"]');
			selectFlavor.setAttribute("name", 'select['+count+'][flavor]');
			console.log(selectFlavor);

			for (var i = 0; i < flavors.length; i++) {

				var option = document.createElement("option");
				option.value = flavors[i];
				option.textContent = flavors[i];
				selectFlavor.appendChild(option);
			}
		}

		// Ice cream can hold more than on flavor.
		function addMoreFlavors() {
			count++;

			// Insert new flavor selection dropdown and input fields
			var select = 'flavor: <select class="selectFlavor" id="flavorSelect['+count+']" ><option >-- select --</option></select>';
			var input =  ' amount: <input class="selectFlavor" type = "text" name ="select['+count+'][amount]" id="amountSelect['+count+']" value="" /> <br />';
			document.getElementById('flavorSection').innerHTML += select;
			document.getElementById('flavorSection').innerHTML += input;

			selectFlavor();
		}
	</script>
</head>
<body>

	<div>
		<b> Flavor options: </b> <br />
		<ul id="flavors"></ul>

	</div>
	<div>
   		<form name = "typeForm" action = "<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method = "POST">
			ice cream: <select name="type" id="type" onchange="updateSecondDrop()">
				<option >-- select --</option>
			</select> <br />
			selection: <select id="special" name="special">
				<option >-- select --</option>
			</select>

			<div id="scoopMax"></div>
			<div id="flavorSection">
				flavor: <select class="selectFlavor" id="flavorSelect[0]" name="select[0][flavor]">
					<option >-- select --</option>
				</select> amount: <input type = "text" class="selectFlavor" id="amountSelect[0]" name ="select[0][amount]" value="" /><br />
			</div>
			<input type="button" onclick="addMoreFlavors()" value="Next flavor" />

			<input type = "submit" />
	      </form>
      </div>

    <div>
		<b>Receipt:</b>
		<ul id="ordered">
		</ul>
	</div>
</body>
</html>
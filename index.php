<?php
	require_once('order.php');
	session_start();
	$message = '';

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		if(($_POST['type']) !== "-- select --") {

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
				$message = "Item not updated into order. Check the amount field. <br /> Maximum amount of scoops in ". $_POST['type'] ." is ". $_SESSION['item']->scoopsLeft. ". <br />" ;
			}
		} else {
			$message = "Cannot submit an empty item.";
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
	<link rel="stylesheet" href="./css/bootstrap.min.css">

	<script type="text/javascript">
		var count = 1;

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

			if ( document.getElementById('scoopMax').innerHTML == '' ) {
				document.getElementById('scoopMax').hidden = true;
			}
		}

		// Updates ice cream options based on selected ice cream type.
		function updateSecondDrop() {
			var iceCreamType = document.getElementById("type").value;
			console.log("selected " + iceCreamType);

			orderItem = items.map(function(e) {return e["name"]}).indexOf(iceCreamType);

			console.log(items[orderItem]);
			if ( items[orderItem] != undefined ) {
				var options = items[orderItem]['options'];
			} else {
				var options = 0;
			} 

			var special = document.getElementById("special");

			document.getElementById("special").innerHTML = "<option >-- select --</option>";

			for (var i = 0; i < options.length; i++) {
				var option = document.createElement("option");
				option.value = options[i];
				option.textContent = options[i];
				special.appendChild(option);
			}

			if ( iceCreamType == "-- select --"){
				document.getElementById('scoopMax').hidden = true;
				document.getElementById('scoopMax').innerHTML = "";
			} else {
				document.getElementById('scoopMax').hidden = false;
				document.getElementById('scoopMax').innerHTML = "Maximum amount of scoops in " + iceCreamType + " is "+items[orderItem]['scoopsMaxLeft'];
			}
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
			let select = count + '. flavor: ' +
						'<select class="selectFlavor form-control" id="flavorSelect['+count+']" name="select[0][flavor]">' +
							'<option >-- select --</option>'
						'</select>';
			let input = 'amount: ' +
						'<input type = "text" class="selectFlavor form-control" id="amountSelect['+count+']" name ="select['+count+'][amount]" value="" />';

			document.getElementById('flavorSection').innerHTML += select;
			document.getElementById('flavorSection').innerHTML += input;

			selectFlavor();
		}
	</script>
</head>
<body>
<div class="container mt-5">
	<div class="row">
		<div class="col-12">
			<h1>Ice Cream Shop</h1>
		</div>
	</div>
	<?php if (strlen($message) > 0 ) : ?>
	<div class="alert alert-warning" role="alert">
		<?php echo $message ?>
	</div>
	<?php endif; ?>
	<div class="row">
		
		<div class="col-6">
	   		<form name = "typeForm" action = "<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method = "POST">
				<div class="form-group">
					<label>Ice Cream: </label>
					
					<select name="type" id="type" onchange="updateSecondDrop()" class="form-control">
						<option >-- Select --</option>
					</select>
				</div>
				<div class="form-group">
					<label>Selection: </label>
				
					<select id="special" name="special" class="form-control">
						<option >-- Select --</option>
					</select>
				</div>
				<div class="form-group">
					<div id="scoopMax" class="alert alert-info" role="alert"></div>
					<div id="flavorSection">
						<label>Flavor:</label> 
						<select class="selectFlavor form-control" id="flavorSelect[1]" name="select[1][flavor]">
							<option >-- Select --</option>
						</select> 
						<label>Amount:</label> 
						<input type = "text" class="selectFlavor form-control" id="amountSelect[1]" name ="select[1][amount]" value="" />
					</div>
					<input type="button" onclick="addMoreFlavors()" value="Next Flavor" class="btn btn-secondary mt-2" />
				</div>
				<input class="btn btn-primary" type="submit" value="Add Ice Cream To Order" />
		      </form>
	      </div>
	      <div class="col-6">
			<h5> Flavor options: </h5> 
			<ul id="flavors"></ul>

		</div>
    </div>
    <hr />
	<div class="row">

	    <div class="col-6">
			<h4>Receipt:</h4>
			<ul id="ordered">
			</ul>
		</div>
	</div>
</div>
</body>
</html>
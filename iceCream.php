<?php


/**
*
*/
class IceCream extends FormChecks {
		public $iceCreamType = '';
		public $scoopsLeft = 0;
		public $optionSelected = '';
		public $options = '';
		public $scoopsOf = '';
		public $price = 0;
		private $database = '';

	function __construct() {
		$this->database = new Data();
	}

	/**
	* Each type of ice creams have different options (exmpl. milkshake uses different types of milks) and scoop limits. These are defined here.
	*/
	public function setIceCreamType($iceCreamType) {
		// Just in case lets check that there is not any illegal characters. Used to be text field.
		$iceCreamType = $this->trimAndLowerCase($iceCreamType);
		switch ($iceCreamType) {
			case 'ice cream':
				$scoopMax = 2;
				break;
			case 'milkshake':
				$this->setPrice('milks', 1);
				$scoopMax = 1;
				break;
			case 'floats':
				$this->setPrice('sodas', 1);
				$scoopMax = 'infinite';
				break;
			default:
				$scoopMax = 0;
				break;
		}
		$this->setScoopsLimit($scoopMax);
		$this->setOptions($iceCreamType);
		$this->iceCreamType = $iceCreamType;

		return $this->iceCreamType;
	}

	/**
	* Collects requested options (milks, sodas, etc.) from data for ice creams.
	*/
	public function setOptions($iceCreamType) {
		$this->options = $this->database->getIceCreamOptions($iceCreamType);

		return $this->options;
	}

	public function getOptions() {
		return $this->options;
	}

	/**
	* Prevents too many scoops to be passed into system. Ice cream machines have their limits for
	* reason.
	* Item prices are defined by the amount of scoops of ice cream used.
	*/
	public function setScoopsOf(array $array) {
		for ($i=0; $i < count($array); $i++) {
			$flavor = $this->trimAndLowerCase($array[$i]['flavor']);
			$amount = $array[$i]['amount'];

			if ($this->scoopsLeft === 'infinite') {
				$this->scoopsOf[$i] = array('flavor' => $flavor, 'amount' => $amount);
				$this->setPrice('scoop', $amount);
			} else if ($this->scoopsLeft >= $amount) {
				$this->scoopsLeft -= $amount;
				$this->scoopsOf[$i] = array('flavor' => $flavor, 'amount' => $amount);
				$this->setPrice('scoop', $amount);
			} else {
				$this->scoopsOf[$i] = array('flavor' => $flavor, 'amount' => $this->scoopsLeft);
				$this->setPrice('scoop', $this->scoopsLeft);
				$this->scoopsLeft = 0;
			}
		}

		return $this->scoopsOf;
	}

	public function setSelection($value) {

		if (in_array($value, $this->options) ) {
			$this->optionSelected = $value;
		}
		return $this->optionSelected;
	}

	public function getScoopsLimit() {
		return $this->scoopsLeft;
	}

	public function setScoopsLimit($scoopsLeft = 0) {
		$this->scoopsLeft = $scoopsLeft;
		return $this->scoopsLeft;
	}

	/**
	* Requests all ice cream options that this ice cream shop has in it's selection.
	*/
	public function getIceCreams($json = 0) {
		$result =  $this->database->select($json, 'iceCreams');
		return $result;
	}

	/**
	* Requests all flavor options that this ice cream shop has in it's selection.
	*/
	public function getFlavors($json = 0) {
		$result =  $this->database->select($json, 'flavors');
		return $result;
	}

	/**
	* Calculates total price for the item.
	*/
	public function setPrice($item, $amount) {
		$value = $this->database->select($json = 0);

		$this->price += ($amount*$value->priceList->$item);
		return $this->price;
	}

	public function getPrice() {
		return $this->price;
	}
}

?>
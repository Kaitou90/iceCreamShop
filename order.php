<?php

require('iceCream.php');
require('data.php');

/**
 *
 *
 */
class Order {
	public $total = array('subTotal' => 0, 'discount' => 0, 'total' => 0);
	public $items = null;
	public $itemCount = 0;
	public $discount = 0;

	public function setItem($value) {
		$currentItem = $this->itemCount;
		$this->itemCount++;
		$this->addToTotal($value);
		$this->items[$currentItem] = $value;

		return $this->items[$currentItem];
	}
	public function getItem($i) {
		return $this->items[$i];
	}

	public function getItems() {
		return $this->items;
	}

	public function addToTotal($value) {

		$this->getDiscount($value->iceCreamType);
		$this->total['subTotal'] += $value->price;
		$this->total['discount'] +=  ($value->price*$this->discount);
		$this->total['total'] += ( $value->price - ( $value->price*$this->discount ) );

		return $this->total;
	}

	public function getDiscount($item) {
		switch ($item) {
			case 'floats':
				$this->discount = 0.1;	// discount of 10%
				break;
			case 'milkshake':
				$this->discount = 0.15; // discount of 15%
				break;
			default:
				$this->discount = 0;	// no discount
				break;
		}
		return $this->discount;
	}

	public function getTotal() {
		return $this->total;
	}
}

/**
*
*/
class FormChecks {

	protected function trimAndLowerCase($value) {
		if(!preg_match('/[^a-zA-Z0-9]+%\s/', $value) ) {
			$value = trim(strtolower($value));
			return $value;
		} else {
			return print_r("illegal character");
		}
	}
}


?>
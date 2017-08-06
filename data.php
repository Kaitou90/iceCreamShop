<?php

/**
*	Mimic of database. Reads information from JSON file and passes it to php classes.
*/
class Data {

	function __construct() {
		$file = 'data.json';
		if(file_exists($file)) {
			$this->string = file_get_contents($file);
		} else {
			$this->string = 0;
		}
	}

	protected function decodeJSON() {
		$this->json = json_decode($this->string);
		return $this->json;
	}

	/**
	* $json = 1 -> Data requested in JSON format.
	* $json = 0 -> Data requested as php object.
	*
	* $item tells what information has been requested. Only in JSON format.
	* Security reasons.
	*/
	public function select($json = 0, $item = null) {
		$result = $this->decodeJSON();

		if ($json) {
			if ($item) {
				$result = json_encode($result->$item);
			} else {
				$result = json_encode($result);
			}
		}

		return $result;
	}

	public function getIceCreamOptions($item) {

		foreach ($this->select()->iceCreams as $key => $value) {
			if ($item === $value->name) {
				$json = $value->options;
			}
		}

		return $json;
	}
}

?>
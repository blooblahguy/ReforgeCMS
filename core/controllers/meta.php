<?

	class Meta extends Magic {
		protected $data;

		function __construct($id = 0) {
			// Define Schema for user table(s)
			// $this->
		}

		function exists($key) {
			return array_key_exists($key, $this->data);
		}

		function set($key, $val) {
			$this->data[$key] = $val;
		}

		function &get($key) {
			return $this->data[$key];
		}

		function clear($key) {
			unset($this->data[$key]);
		}
	}

?>
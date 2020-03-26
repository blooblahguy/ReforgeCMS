<?




class RF_Mapper extends Magic {
    protected $data
		, $db
		, $schema
		, $_id
		, $table;

	function __construct($db, $table, $schema, $cache_key) {

	}

    function exists($key) {
        return array_key_exists($key,$this->data);
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


	// SQL Functionality
	function save() {}
	function select() {}
	function exec() {}
}

?>
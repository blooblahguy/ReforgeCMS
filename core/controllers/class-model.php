<?

class RF_Model extends \DB\SQL\Mapper {
	public 
		$table_schema,
		$sc_name,
		$sl_name,
		$model_table;

	private $ttl_default = 10000;

	function __construct() {
		global $db;

		$this->sc_name = $this->model_table."_schema";
		$this->sl_name = $this->model_table;

		// add to schema builder
		// $this->register_schema();

		// cache table construct
		$schema_cache = $this->get_cache($this->sc_name);
		parent::__construct($db, $this->model_table, null, $schema_cache);
		$this->set_cache($this->sc_name);

		// Cache Wipers
		$this->afterinsert(function($self, $pkeys) {
			$this->clear_cache($this->sl_name);
		});
		$this->afterupdate(function($self, $pkeys) {
			$this->clear_cache($this->sl_name);
		});
		$this->aftersave(function($self, $pkeys) {
			$this->clear_cache($this->sl_name);
		});
		$this->aftererase(function($self, $pkeys) {
			$this->clear_cache($this->sl_name);
		});
	}

	/**
	 * Cache Shortcuts
	 */
	function clear_cache($key) {
		$cache = \Cache::instance();

		$cache->clear($key);
	}
	function set_cache($key) {
		$cache = \Cache::instance();

		$cached = $cache->get($key);
		if (! $cached) {
			$cache->set($key, $this->ttl_default);
		}
	}
	function get_cache($key) {
		$cache = \Cache::instance();
		$cached = $cache->get($key);
		if (! $cached) {
			$cached = 0;
		}

		return $cached;
	}

	// Cache Wrappers
	function select($fields,$filter=NULL,array $options=NULL,$ttl=0) {
		$cached = $this->get_cache($this->sl_name);
		return parent::select($fields, $filter, $options, $cached);
		$this->set_cache($this->sl_name);
	}

	// Schema builder / throttler
	private function hash($array) {
		return md5(serialize($array));
	}

	// function register_schema() {
	// 	$cached = $this->cache->get($this->model_table.":schema");
	// 	$update = false;

	// 	if (! $cached) {
	// 		$update = true;
	// 	} elseif ($cached != $this->hash($this->table_schema) ) {
	// 		$update = true;
	// 	}

	// 	if ($update) {
	// 		RFSchema::instance()->add($this->model_table, $this->table_schema);
	// 		$this->cache->set($this->model_table.":schema", $this->hash($this->table_schema));
	// 	}
	// }
}


function get_cache($key) {
	$cache = \Cache::instance();
	$cached = $cache->get($key);
	if (! $cached) {
		$cached = 0;
	}

	return $cached;
}

function clear_cache($key) {
	$cache = \Cache::instance();
	$cache->clear($key);
}

function set_cache($key) {
	$cache = \Cache::instance();
	$cached = $cache->get($key);
	if (! $cached) {
		$cache->set($key, 10000);
	}
}

?>
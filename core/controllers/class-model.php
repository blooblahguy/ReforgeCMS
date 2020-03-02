<?

class RF_Model extends \DB\SQL\Mapper {
	public 
		$model_schema,
		$model_table,
		$ttl_default = 10000,
		$caches = array();

	function __construct() {
		global $db;

		// build default caches
		$this->caches["schema"] = $this->model_table."_schema";
		$this->caches["fields"] = $this->model_table."_fields";

		// add to schema builder
		$this->register_schema();

		// cache table construct
		parent::__construct($db, $this->model_table, null, $this->get_cache("schema"));
		$this->set_cache("schema");

		// Cache Wipers
		$this->afterinsert(function($self, $pkeys) {
			$this->clear_cache("fields");
		});
		$this->afterupdate(function($self, $pkeys) {
			$this->clear_cache("fields");
		});
		$this->aftersave(function($self, $pkeys) {
			$this->clear_cache("fields");
		});
		$this->aftererase(function($self, $pkeys) {
			$this->clear_cache("fields");
		});
	}

	/**
	 * Cache Shortcuts
	 */

	function clear_cache($key) {
		$cache = \Cache::instance();
		$key = $this->caches[$key];

		$cache->clear($key);
	}
	function set_cache($key, $value = null) {
		$cache = \Cache::instance();
		$key = $this->caches[$key];

		$cached = $cache->get($key);
		if (! $cached) {
			if (! $value) { $value == $this->ttl_default; }
			$cache->set($key, $value);
			$cached = $value;
		}

		return $cached;
	}
	function get_cache($key) {
		$cache = \Cache::instance();
		$key = $this->caches[$key];

		$cached = $cache->get($key);
		if (! $cached) {
			$cached = 0.001;
		}

		return $cached;
	}

	// Cache Wrappers
	function select($fields,$filter=NULL,array $options=NULL,$ttl=0) {
		$cached = $this->get_cache("fields");
		$this->set_cache("fields");

		return parent::select($fields, $filter, $options, $cached);
	}

	// Schema builder / throttler
	private function hash($array) {
		return md5(serialize($array));
	}

	function register_schema() {
		if (! $this->model_schema) { return; }

		// Update this schema
		RF_Schema::instance()->add($this->model_table, $this->model_schema);
	}
}

?>
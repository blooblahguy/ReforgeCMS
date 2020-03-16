<?

class RF_Model extends \DB\SQL\Mapper {
	public 
		$model_schema,
		$model_table,
		$disable_created = false,
		$disable_modified = false,
		$ttl_default = 10000,
		$caches = array();

	function __construct() {
		global $db;

		// build default caches
		$this->caches["schema"] = $this->model_table."_schema";
		$this->caches["fields"] = $this->model_table."_fields";

		// add to schema builder
		$updating = $this->register_schema();
		if ($updating) {
			$this->clear_cache("schema");
		}

		// cache table construct
		parent::__construct($db, $this->model_table, null, $this->get_cache("schema"));
		$this->set_cache("schema");

		// Cache Wipers
		$this->afterinsert(function($self, $pkeys) {
			$self->clear_cache("fields");
		});
		$this->afterupdate(function($self, $pkeys) {
			$self->clear_cache("fields");
		});
		$this->aftersave(function($self, $pkeys) {
			$self->clear_cache("fields");
		});
		$this->aftererase(function($self, $pkeys) {
			$self->clear_cache("fields");
		});
	}

	function set_object($object) {
		foreach ($this->model_schema as $key => $info) {
			$this[$key] = $object[$key];
		}

		if (isset($object['id'])) {
			$this->id = $object['id'];
		}
		if (isset($object['created'])) {
			$this->created = $object['created'];
		}
		if (isset($object['modified'])) {
			$this->modified = $object['modified'];
		}
	}

	/**
	 * Cache Shortcuts
	 */

	function clear_cache($key) {
		$cache = \Cache::instance();
		$key = $this->caches[$key];

		$cache->clear($key);
	}
	function set_cache($key, $value = false) {
		$cache = \Cache::instance();
		if ($key == "fields") { return; }
		$key = $this->caches[$key];

		$cached = $cache->get($key);
		// if ($this->model_table == "options") {
		// 	var_dump($cached, "test");
		// }
		if (! $cached || $cached == 0.001) {
			if (! $value) { $value = $this->ttl_default; }
			$cache->set($key, $value);
			$cached = $value;
		}

		return $cached;
	}
	function get_cache($key) {
		$cache = \Cache::instance();
		$key = $this->caches[$key];

		if ($cache->exists($key)) {
			return $cache->get($key);
		} else {
			return $cache->clear($key);
		}

		// $cached = $cache->get($key);
		// if ($this->model_table == "options") {
		// 	debug("test");
		// 	var_dump($key, $cached);
		// }
		// if (! $cached) {
		// 	$cached = NULL;
		// }

		// return $cached;
	}

	function query($cmds, $args = NULL, $ttl = 0, $log=TRUE, $stamp = FALSE) {
		global $db;
		$ttl = $this->get_cache("fields");
		// debug($this->caches["fields"]);
		$rs = $db->exec($cmds, $args, $ttl, $log, $stamp);
		$this->set_cache("fields");

		return $rs;
	}

	// Cache Wrappers
	function select($fields, $filter = NULL, array $options = NULL, $ttl = 0) {
		$ttl = $this->get_cache("fields");
		$this->set_cache("fields");

		// debug($this->model_table);

		// if ($this->model_table == "options") {

		// 	debug($fields);
		// 	debug($ttl);
		// }

		return parent::select($fields, $filter, $options, $ttl);
	}

	// Schema builder / throttler
	private function hash($array) {
		return md5(serialize($array));
	}

	function register_schema() {
		if (! $this->model_schema) { return; }

		// Update this schema
		return \RF_Schema::instance()->add($this->model_table, $this->model_schema, array(
			"disable_created" => $this->disable_created, 
			"disable_modified" => $this->disable_modified
		));
	}
}

?>
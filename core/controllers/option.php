<?

	class Option extends \DB\SQL\Mapper {
		function __construct($ttl = 10000) {
			global $db, $core;
			if ($core->get("schema_updated")) {
				$ttl = 0;
			}

			parent::__construct( $db, 'options', null, $ttl);
		}
	}

?>
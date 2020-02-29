<?

	class PostType extends \DB\SQL\Mapper {
		function __construct($ttl = 10000) {
			global $db, $core;
			if ($core->get("schema_updated")) {
				$ttl = 0;
			}

			parent::__construct( $db, 'post_types' );
		}
	}

?>
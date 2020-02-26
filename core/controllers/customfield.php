<?

	class CustomField extends \DB\SQL\Mapper {
		function __construct($ttl = 10000) {
			global $db, $core;
			if ($core->get("schema_updated")) {
				$ttl = 0;
			}

			parent::__construct( $db, 'custom_fields' );
		}

		function get_fields() {
			if ($this->fieldset) {
				return unserialize($this->fieldset);
			} else {
				return array();
			}
		}
	}

?>
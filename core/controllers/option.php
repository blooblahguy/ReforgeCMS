<?


	class Option extends \DB\SQL\Mapper {
		function __construct($id = 0) {
			global $db;

			parent::__construct( $db, 'options' );
		}
	}

?>
<?

	class PostType extends \DB\SQL\Mapper {
		function __construct($id = 0) {
			global $db;

			parent::__construct( $db, 'post_types' );
		}
	}

?>
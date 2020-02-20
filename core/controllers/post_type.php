<?

	class PostType extends \DB\SQL\Mapper {
		private $logged_in = false, $self = false, $cookie_uid = 0, $token = 0;

		function __construct($id = 0) {
			global $db;

			parent::__construct( $db, 'post_types' );
		}
	}

?>
<?

	class RF_Hook {
		public $callbacks = array();

		function unique_func($function) {
			if ( is_string( $function ) ) {
				return $function;
			}

			// Convert to array
			if ( is_object( $function ) ) {
				$function = array( $function, '' );
			} else {
				$function = (array) $function;
			}

			if ( is_object( $function[0] ) ) {
				return spl_object_hash( $function[0] ) . $function[1];
			} elseif ( is_string( $function[0] ) ) {
				return $function[0] . '::' . $function[1];
			}
		}

		/**
		 * Filter Functions
		 * @param mixed $value	
		 * @param array $args	
		 */
		function apply_filters($value, $args) {
			if (! $this->callbacks) { return $value; }
			$num_args = count($args);

			// simply call through each callback and keep updating the value
			// not going to worry about whether we determined the # of arguments ahead of time
			foreach ($this->callbacks as $priority => $keys) {
				foreach ($keys as $callback) {
					$value = call_user_func_array( $callback, $args );
				}
			}

			return $value;
		}

		/**
		 * 
		 */
		function add_filter($action, $func, $priority = 10) {
			$key = $this->unique_func($func);
			$priority_existed = isset( $this->callbacks[ $priority ] );
	
			$this->callbacks[ $priority ][ $key ] = $func;

			// if we're adding a new priority to the list, put them back in sorted order
			if ( ! $priority_existed && count( $this->callbacks ) > 1 ) {
				ksort( $this->callbacks, SORT_NUMERIC );
			}
		}

		/**
		 * 
		 */
		function remove_filter($action, $func, $priority = 10) {
			$key = $this->unique_func($func);

			$exists = isset( $this->callbacks[ $priority ][ $key ] );
			if ( $exists ) {
				unset( $this->callbacks[ $priority ][ $key ] );
				if ( ! $this->callbacks[ $priority ] ) {
					unset( $this->callbacks[ $priority ] );
				}
			}
		}

		/**
		 * 
		 */
		function do_action($args) {
			$this->apply_filters("", $args);
		}
	}


	/**
	 * Wrapper Functions
	 * FILTERS
	 */
	function apply_filters($action, $value) {
		global $rf_filters;
		$args = func_get_args();

		if (! $rf_filters[$action]) {
			return $value;
		}

		array_shift( $args );
		$filtered = $rf_filters[$action]->apply_filters($value, $args);

		return $filtered;
	}


	function add_filter( $action, $func, $priority = 10 ) {
		global $rf_filters;
		if ( ! isset( $rf_filters[ $action ] ) ) {
			$rf_filters[ $action ] = new RF_Hook();
		}
		$rf_filters[ $action ]->add_filter( $action, $func, $priority );
		return true;
	}

	function remove_filter( $action, $func, $priority = 10 ) {
		global $rf_filters;

		$r = false;
		if ( isset( $rf_filters[ $action ] ) ) {
			$r = $rf_filters[ $action ]->remove_filter( $action, $func, $priority );
			if ( ! $rf_filters[ $action ]->callbacks ) {
				unset( $rf_filters[ $action ] );
			}
		}
		return $r;
	}

	/**
	 * Wrapper Functions
	 * ACTIONS
	 */

	function do_action($action, ...$args) {
		global $rf_filters;

		if (! $rf_filters[ $action ] ) { return; }

		if ( empty($args) ) { $args[] = ''; }

		$rf_filters[ $action ]->do_action( $args );
	}

	function add_action($action, $func, $priority = 10) {
		return add_filter( $action, $func, $priority );
	}

	function remove_action($action, $func, $priority = 10) {
		return remove_filter( $action, $func, $priority );
	}
	

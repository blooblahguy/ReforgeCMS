<?
class rcf_rule {
	public $choices = array();

	protected function __construct() {
		global $core;

		$this->choices = $this->rule_choices();

		$core->route("GET /core/custom_fields/rules/{$this->name}", "{$this->rule_class}->render_choices");
		// add_action($this->action, $this->rule_match);

		// register in main class
		RCF::instance()->register_rule_type($this);
	}

	function render_choices() {
		foreach ($this->choices as $key => $value) {
			echo "<option value='{$key}'>{$value}</option>";
		}
	}

	protected function compare($value, $rule) {
		$match = ( $value == $rule['value'] );
		
        if ( $rule['value'] == 'all' ) $match = true;

        if ( $rule['operator'] == '!=' ) {
        	$match = ! $match;
        }
 
		return $match;
	}

}

?>
<?
class rcf_rule {
	public $choices = array();

	protected function __construct() {
		global $core;

		// $core->route("GET /admin/custom_fields/rules/{$this->name}", "{$this->rule_class}->render_choices");
		$core->route("GET /admin/custom_fields/rules/@slug", "RCF->ajax_render_choices");

		// register in main class
		RCF::instance()->register_rule_type($this);
	}

	function render_choices($current = false) {
		$this->choices = $this->rule_choices();
		foreach ($this->choices as $key => $value) {
			$selected = "";
			if ($key == $current) {$selected = " selected";}
			echo "<option value='{$key}'{$selected}>{$value}</option>";
		}
	}

	protected function compare($value, $rule) {
		$match = ( $value == $rule['value'] );
		
        if ( $rule['value'] == 'all' ) $match = true;

        if ( $rule['expression'] == '!=' ) {
        	$match = ! $match;
        }
 
		return $match;
	}

}


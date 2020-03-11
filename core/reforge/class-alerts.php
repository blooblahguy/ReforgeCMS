<?
	class Alerts extends Prefab {
		private function add($level, $message) {
			global $core; 
			// debug();
			$stored = $core->get("SESSION.alerts");
			if (! $stored) {
				$core->set("SESSION.alerts", array());
			}

			$stored[$level] = isset($stored[$level]) ? $stored[$level] : array();
			$stored[$level][$message] = $message;

			$core->set("SESSION.alerts", $stored);
		}

		function message($message) {
			$this->add("info", $message);
		}
		function info($message) {
			$this->add("info", $message);
		}
		function error($message) {
			$this->add("error", $message);
		}
		function warning($message) {
			$this->add("warning", $message);
		}
		function success($message) {
			$this->add("success", $message);
		}		

		function display($level = "all") {
			global $core;

			if ($level == "all") {
				$stored = $core->get("SESSION.alerts");
				if (! $stored) {return;}
				foreach ($stored as $level => $messages) {
					$message = "";
					foreach ($messages as $key => $string) {
						$message .= "<div>$string</div>";
					}
					$this->print($level, $message);
				}
				
				$stored = array();
				$core->set("SESSION.alerts", $stored);
			} else {
				$stored = $core->get("SESSION.alerts");
				if (! $stored) {return;}
				foreach ($stored[$level] as $level => $messages) {
					$message = "";
					foreach ($messages as $key => $string) {
						$message .= "<div>$string</div>";
					}
					$this->print($level, $message);
				}
				unset($stored[$level]);
				$core->set("SESSION.alerts", $stored);
			}

		}

		private function print($level, $message) {
			echo "<div class='message-$level'>$message</div>";
		}
	}


	function display_alerts($level = "all") {
		\Alerts::instance()->display($level);
	}

	function add_alert($type, $message) {
		\Alerts::instance()->$$type($message);
	}
?>
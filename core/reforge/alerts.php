<?

class Alerts extends Prefab {
	private function add($level, $message) {
		// global $core; 
		// debug();
		$stored = session()->get("alerts");
		if (! $stored) {
			session()->set("alerts", array());
		}

		$stored[$level] = isset($stored[$level]) ? $stored[$level] : array();
		$stored[$level][$message] = $message;

		session()->set("alerts", $stored);
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
		if ($core->ERROR) { return; }
		$session = session();

		if ($level == "all") {
			if (! method_exists($session, "get")) {
				return;
			}
			$stored = $session->get("alerts");
			if (! $stored) {return;}
			echo '<div class="message_outer container pady2">';
			foreach ($stored as $level => $messages) {
				$message = "";
				foreach ($messages as $key => $string) {
					$message .= "<div>$string</div>";
				}
				$this->print($level, $message);
			}
			
			$stored = array();
			$session->set("alerts", $stored);
			echo '</div>';
		} else {
			if (! method_exists($session, "get")) {
				return;
			}
			$stored = $session->get("alerts");
			if (! $stored) {return;}
			echo '<div class="message_outer container pady2">';
			foreach ($stored[$level] as $level => $messages) {
				$message = "";
				foreach ($messages as $key => $string) {
					$message .= "<div>$string</div>";
				}
				$this->print($level, $message);
			}
			unset($stored[$level]);
			$session->set("alerts", $stored);
			echo '</div>';
		}

	}

	private function print($level, $message) {
		echo "<div class='message-$level'>$message</div>";
	}
}

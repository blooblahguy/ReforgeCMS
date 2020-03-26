<?
	class Menu extends \RF\Mapper {
		function __construct() {
			$schema = array(
				"slug" => array(
					"type" => "VARCHAR(190)",
					"unique" => true
				),
				"label" => array(
					"type" => "VARCHAR(256)"
				),
				"links" => array(
					"type" => "LONGTEXT"
				),
				"order" => array(
					"type" => "INT(3)"
				)
			);

			parent::__construct("menus", $schema);
		}

		function link_loop($links) {
			foreach ($links as &$l) {
				$l['html'] = $this->build_link_html($l);
				// unset($l['class']);
				// unset($l['target']);

				if (count($l['children']) > 0) {
					$l['children'] = $this->link_loop($l['children']);
				} else {
					unset($l['children']);
				}
			}

			return $links;
		}

		function get_menu_array() {
			if (! $this->id) { return array(); }

			$links = unserialize($this->links);
			if (! $links) {
				$links = array();
			}


			$links = $this->link_loop($links);
			// debug($links);

			return $links;
		}

		function build_link_html($link) {
			$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
			// list($CONTROLLER) = explode("/", $PATH);

			// debug($path);
			// debug($CONTROLLER);

			if (strpos($link['url'], "http") === false && $link['url'] != "#0") {
				$link['url'] = "/".$link['url'];
				if ($link['url'] == $path) {
					$link['class'] .= " active";
				} elseif (strpos($path, $link['url']) !== false) {
					$link['class'] .= " ancestor";
				}
			}

			if ($link['url'] == "#0" || $link['url'] == "") {
				// unset($link['url']);
				// unset($link['target']);
				$link['class'] .= " head_link";
			}

			$link['class'] = explode(" ", trim($link['class']));
			if (reset($link['class']) == "") {
				$attrs['class'] = false;
			} else {
				$link['class'] = implode(" ", $link['class']);
			}

			$attrs = array();
			$attrs['href'] = $link['url'];
			$attrs['class'] = $link['class'];
			$attrs['target'] = $link['target'] ? "_blank" : false;
			if ($link['url'] == "#0" || $link['url'] == "") {
				unset($attrs['href']);
				unset($attrs['target']);
			}

			$attrs = array_map(function($key, $value) {
				if (gettype($value) == "boolean" && $value === true) {
					return $key;
				} elseif (gettype($value) == "string") {
					return $key.'="'.$value.'"';
				}
			}, array_keys($attrs), array_values($attrs));
			$attrs = implode(' ', $attrs);

			if ($link['url'] && $link['url'] != "#0" && $link['url'] != "") {
				return "<a $attrs>{$link['label']}</a>";
			} else {
				return "<div $attrs>{$link['label']}</div>";
			}
			

		}
	}
?>
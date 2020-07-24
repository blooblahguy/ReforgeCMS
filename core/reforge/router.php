<?php

final class Route extends Prefab {
	private $routes = [],
		$aliases = [],
		$store = [],
		$base = [];

	// contruct with base url
	function __construct($base = '') {
		$this->base = $base;
		$_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], strlen($this->base));

		// store basic information
		$this->store['method'] = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
		$this->store['https'] = $_SERVER['HTTPS'] == "on" ? "https" : "http";
		$this->store['uri'] = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
		$this->store['url'] = parse_url($this->store['https'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	}

	/**
	 * Trim and split and the same time
	 */
	function split($str, $noempty = true) {
		return array_map('trim', preg_split('/[,;|]/', $str, 0, $noempty ? PREG_SPLIT_NO_EMPTY : 0));
	}

	/**
	 * add a pattern and handler to our route list
	 */
	function route($pattern, $handler, $name = null) {
		// seperate out parts of the pattern
		preg_match('/([\|\w]+)\h+(?:(?:@?(.+?)\h*:\h*)?(@(\w+)|[^\h]+))/u', $pattern, $parts);

		// here's what we've found
		$methods = $parts[1];
		$alias = $parts[2];
		$url = $parts[3];

		// check if this is an alias route
		if ($alias) {
			if (preg_match('/^\w+$/', $parts[2])) {
				$this->aliases[$parts[2]] = $parts[3];
			}
		}

		// now loop through listed methods and add the route
		foreach ($this->split($methods) as $verb) {
			$this->routes[$url][strtoupper($verb)] = [$handler, $ttl, $kbps, $alias];
		}
	}

	/**
	*	Applies the specified URL mask and returns parameterized matches
	*	@return $args array
	*	@param $pattern string
	*	@param $url string|null
	**/
	function mask($pattern) {
		$url = $this->store['uri'];
			
		// find the wildcard
		$wild = preg_quote($pattern, '/');
		$i = 0;
		while (is_int($pos = strpos($wild,'\*'))) {
			$wild = substr_replace($wild, '(?P<_'.$i.'>[^\?]*)', $pos, 2);
			$i++;
		}

		// match wilds and seperate paths
		preg_match('/^'.preg_replace('/((\\\{)?@(\w+\b)(?(2)\\\}))/', '(?P<\3>[^\/\?]+)', $wild).'\/?$/ium', $url, $args);
		foreach (array_keys($args) as $key) {
			if (preg_match('/^_\d+$/', $key)) {
				if (empty($args['*'])) {
					$args['*'] = $args[$key];
				} else {
					if (is_string($args['*'])) {
						$args['*'] = [$args['*']];
					}
					array_push($args['*'], $args[$key]);
				}
				unset($args[$key]);
			} elseif (is_numeric($key) && $key) {
				unset($args[$key]);
			}
		}

		return $args;
	}

	function run() {
		$paths = [];
		$keys = array_keys($this->routes);

		// prioritize non wild-card routes
		foreach ($keys as $key) {
			$path = preg_replace('/@\w+/','*@',$key);
			if (substr($path,-1) != '*') {
				$path.='+';
			}
			$paths[]=$path;
		}

		$vals = array_values($this->routes);
		array_multisort($paths, SORT_DESC, $keys, $vals);
		$this->routes = array_combine($keys, $vals);

		$allowed = [];
		foreach ($this->routes as $pattern => $routes) {
			$args = $this->mask($pattern);
			// didn't find a match in this route mask
			if (! $args) {
				continue;
			}
			
			ksort($args);

			list($handler, $ttl, $kbps, $alias) = $routes;
		}
	}
}

function route() {

}
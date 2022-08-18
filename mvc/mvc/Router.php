<?php
class Router {
	protected $_convertedRoutes;
	
	public function __construct($routedef) {
		$this->_convertedRoutes = $this->routeConverter($routedef);
	}
	
	public function routeConverter($routedef) {
		$converted = array();
		foreach ($routedef as $url => $par) {
			$converts = explode('/', ltrim($url, '/'));
			foreach ($converts as $i => $convert) {
				if (0 === strpos($convert, ':')) {
					$bar = substr($convert, 1);
					$convert = '(?<' . $bar . '>[^/]+)';
				}
				$converts[$i] = $convert;
			}
			$pattern = '/' . implode('/', $converts);
			$converted[$pattern] = $par;
		}
		return $converted;
	}
	
	public function getRouteParams($path) {
		if ('/' !== substr($path, 0, 1)) {
			$path = '/' . $path;
		}
		foreach ($this->_convertedRoutes as $pattern => $par) {
			if (preg_match('#^' . $pattern . '$#', $path, $p_match)) {
				$par = array_merge($par, $p_match);
				return $par;
			}
		}
		return false;
	}
}
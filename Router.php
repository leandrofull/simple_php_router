<?php
	// PHP Version: 8.0.23

	// Request Example
	$url = 'client/2';
	$url = strtolower($url);
	if(substr($url, -1) == "/") $url = substr($url, 0, -1);

	// Router Class
	class Router {
		private function __construct() {}
		private function __clone() {}

		/** 
		 * @var array
		 */
		private static $routes = [];

		/**
		 * @param string
		 */
		public static function start($endpoint): void {
			$explode = explode("/", $endpoint, 2);
			$endpoint = "/".$endpoint;
			$urlBase = array_shift($explode);
			isset($explode[0])? $urlParams = $explode[0] : $urlParams = "";

			if(method_exists("Router", $_SERVER['REQUEST_METHOD'])) {
				self::{$_SERVER['REQUEST_METHOD']}($urlParams);
			} 

			self::run($endpoint, $urlParams);
		}

		/**
		 * @param string
		 */
		private static function GET($urlParams): void {
			self::route("/client/{$urlParams}", function($urlParams) {
				if(count($urlParams) != 1) self::error404();

				echo "ID: {$urlParams[0]}";
			});

			self::route("/client/details", function() {
				echo "Client Details";
			});

			self::route("/clients", function() {
				echo "My Clients";
			});
		}

		/**
		 * @param string
		 */
		private static function POST($urlParams): void {

		}

		/**
		 * @param string
		 * @param closure
		 */
		private static function route($newRoute, $callback): void {
			self::$routes[$newRoute] = $callback;
		}

		/**
		 * @param string
		 * @param string
		 */
		private static function run($endpoint, $urlParams): void {
			if(isset(self::$routes[$endpoint])) {
				self::$routes[$endpoint](explode("/", $urlParams));
			} else {
				self::error404();
			}
		}

		private static function error404(): void {
			http_response_code(404);
			exit();
			die();
		}
	}

	// Router Call
	Router::start($url);

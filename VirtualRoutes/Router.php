<?php
	
	namespace WPVirtualRoutes;
	
	class Router implements RouterInterface {
		
		public static $router;
		
		private $routes;
		/**
		 * @var RouteInterface
		 */
		private $matched;
		
		function __construct() {
			$this->routes = new \SplObjectStorage;
			
			Router::$router = $this; // Make it available globaly
		}
		
		function init() {
			do_action('wp_virtual_routes', $this);
		}
		
		/**
		 * Register a route object in the controller
		 *
		 * @param  \WPVirtualRoutes\RouteInterface $route
		 *
		 * @return \WPVirtualRoutes\RouteInterface
		 */
		function addRoute(RouteInterface $route) {
			$this->routes->attach($route);
			
			return $route;
		}
		
		function dispatch($bool, \WP $wp) {
			if ($this->checkRequest() && $this->matched instanceof Route) {
				$wp->virtual_page = $this->matched;
				do_action('parse_request', $wp);
				$this->setupQuery();
				do_action('wp', $wp);
				$this->matched->getHandler()->handle();
				$this->handleExit();
			}
			
			return $bool;
		}
		
		private function checkRequest() {
			$this->routes->rewind();
			$path = trim($this->getPathInfo(), '/');
			while ($this->routes->valid()) {
				if (trim($this->routes->current()->getUrl(), '/') === $path) {
					$this->matched = $this->routes->current();
					
					return true;
				}
				$this->routes->next();
			}
		}
		
		private function getPathInfo() {
			$home_path = parse_url(home_url(), PHP_URL_PATH);
			
			$url = preg_replace("#^/?{$home_path}/#", '/', add_query_arg(array()));
			
			$pathInfo = [];
			preg_match('/([\w\/]+)\??/', $url, $pathInfo);
			
			return $pathInfo[1];
		}
		
		private function setupQuery() {
			global $wp_query;
			$wp_query->init();
			$wp_query->is_page = true;
			$wp_query->is_singular = true;
			$wp_query->is_home = false;
			$wp_query->found_posts = 1;
			$wp_query->post_count = 1;
			$wp_query->max_num_pages = 1;
			$posts = (array)apply_filters('the_posts', array($this->matched->asWpPost()), $wp_query);
			$post = $posts[0];
			$wp_query->posts = $posts;
			$wp_query->post = $post;
			$wp_query->queried_object = $post;
			$GLOBALS['post'] = $post;
			$wp_query->virtual_page = $post instanceof \WP_Post && isset($post->is_virtual) ? $this->matched : null;
		}
		
		public function handleExit() {
			exit();
		}
		
		public static function redirect(string $title) {
			$routes = self::$router->routes;
			$routes->rewind();
			
			while ($routes->valid()) {
				if (trim($routes->current()->getTitle(), '/') === trim($title, '/')) {
					wp_redirect($routes->current()->getUrl());
					
					return true;
				}
				$routes->next();
			}
			
			return false;
		}
		
		public static function getUrlByTitle(string $title) {
			$routes = self::$router->routes;
			$routes->rewind();
			
			while ($routes->valid()) {
				if (trim($routes->current()->getTitle(), '/') === trim($title, '/')) {
					return $routes->current()->getUrl();
				}
				$routes->next();
			}
			
			return false;
		}
		
	}
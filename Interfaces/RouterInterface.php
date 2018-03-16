<?php
	
	namespace WPVirtualRoutes;
	
	interface RouterInterface {
		
		/**
		 * Init the controller, fires the hook that allow consumer to add routes
		 */
		function init();
		
		/**
		 * Register a route object in the controller
		 *
		 * @param  \WPVirtualRoutes\RouteInterface $route
		 *
		 * @return \WPVirtualRoutes\RouteInterface
		 */
		function addRoute(RouteInterface $route);
		
		/**
		 * Run on 'do_parse_request' and if the request is for one of the registerd routes
		 * setup global variables, fire core hooks, pass handling to the page and exit.
		 *
		 * @param boolean $bool The boolean flag value passed by 'do_parse_request'
		 * @param \WP     $wp   The global wp object passed by 'do_parse_request'
		 */
		function dispatch($bool, \WP $wp);
		
	}
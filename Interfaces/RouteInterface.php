<?php
	
	namespace WPVirtualRoutes;
	
	interface RouteInterface {
		
		function getUrl();
		
		function getTitle();
		
		/**
		 * @param $title
		 *
		 * @return RouteInterface $this
		 */
		function setTitle($title);
		
		/**
		 * @param $content
		 *
		 * @return RouteInterface $this
		 */
		function setContent($content);
		
		/**
		 * @param RouteHandlerInterface $handler
		 *
		 * @return RouteHandlerInterface
		 */
		function setHandler(RouteHandlerInterface $handler);
		
		/**
		 * @return RouteHandlerInterface
		 */
		function getHandler();
		
		/**
		 * Get a WP_Post build using virtual Page object
		 *
		 * @return \WP_Post
		 */
		function asWpPost();
		
	}
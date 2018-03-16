<?php
	
	namespace WPVirtualRoutes;
	
	/*
		Plugin Name: WP Virtual Routes
		Description: WP Virtual Routes
		Author: davisriska
		License: MIT
		Credits: Huge credits go to Giuseppe Mazzapica. I took his base code and rewrote it to suite my needs and wants.
	*/
	
	require_once 'Interfaces\RouterInterface.php';
	require_once 'Interfaces\RouteInterface.php';
	require_once 'Interfaces\RouteHandlerInterface.php';
	require_once 'Handlers\RequestHandler.php';
	require_once 'VirtualRoutes\Route.php';
	require_once 'VirtualRoutes\Router.php';
	
	$router = new Router();
	
	add_action('init', array($router,
	                         'init'
	));
	
	add_filter('do_parse_request', array($router,
	                                     'dispatch'
	), PHP_INT_MAX, 2);
	
	add_action('loop_end', function (\WP_Query $query) {
		if (isset($query->virtual_page) && !empty($query->virtual_page)) {
			$query->virtual_page = null;
		}
	});
	
	add_filter('the_permalink', function ($plink) {
		global $post, $wp_query;
		if ($wp_query->is_page && isset($wp_query->virtual_page) && $wp_query->virtual_page instanceof Route && isset($post->is_virtual) && $post->is_virtual) {
			$plink = home_url($wp_query->virtual_page->getUrl());
		}
		
		return $plink;
	});

<?php
	
	namespace WPVirtualRoutes;
	
	class Route implements RouteInterface {
		
		private $url;
		private $title;
		private $content;
		private $wp_post;
		private $handler;
		
		function __construct($url, $handler, $title) {
			$this->url = filter_var($url, FILTER_SANITIZE_URL);
			$this->setTitle($title);
			$this->setHandler($handler);
		}
		
		function getUrl() {
			return $this->url;
		}
		
		function getTitle() {
			return $this->title;
		}
		
		function setTitle($title) {
			$this->title = filter_var($title, FILTER_SANITIZE_STRING);
			
			return $this;
		}
		
		function setContent($content) {
			$this->content = $content;
			
			return $this;
		}
		
		function setHandler(RouteHandlerInterface $handler) {
			$this->handler = $handler->setRoute($this);
			
			return $handler;
		}
		
		function getHandler() {
			return $this->handler;
		}
		
		function asWpPost() {
			if (is_null($this->wp_post)) {
				$post = array('ID'             => 0,
				              'post_title'     => $this->title,
				              'post_name'      => sanitize_title($this->title),
				              'post_content'   => $this->content ?: '',
				              'post_excerpt'   => '',
				              'post_parent'    => 0,
				              'menu_order'     => 0,
				              'post_type'      => 'page',
				              'post_status'    => 'publish',
				              'comment_status' => 'closed',
				              'ping_status'    => 'closed',
				              'comment_count'  => 0,
				              'post_password'  => '',
				              'to_ping'        => '',
				              'pinged'         => '',
				              'guid'           => home_url($this->getUrl()),
				              'post_date'      => current_time('mysql'),
				              'post_date_gmt'  => current_time('mysql', 1),
				              'post_author'    => is_user_logged_in() ? get_current_user_id() : 0,
				              'is_virtual'     => true,
				              'filter'         => 'raw'
				);
				$this->wp_post = new \WP_Post((object)$post);
			}
			
			return $this->wp_post;
		}
		
	}
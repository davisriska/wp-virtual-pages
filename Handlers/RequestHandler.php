<?php
	
	namespace WPVirtualRoutes;
	
	
	class RequestHandler implements RouteHandlerInterface {
		
		protected $templates;
		/**
		 * @var RouteInterface
		 */
		protected $route;
		
		public function __construct($template = '') {
			$this->templates = [$template,
			                    'page.php',
			                    'index.php',
			
			];
		}
		
		public function setRoute(RouteInterface $route) {
			$this->route = $route;
			
			return $this;
		}
		
		public function handle() {
			
			$this->setBodyClass();
			
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				$this->post();
			} else {
				$this->get();
			}
			
		}
		
		/*
		 * Adds templates name with -data to body class, only needed if using soberwp/controller
		 */
		public function setBodyClass() {
			add_filter('body_class', function ($classes) {
				
				$found = [];
				preg_match('/\/(\w+)(?:\.blade)?\.php$/', $this->templates[0], $found);
				
				return array_merge($classes, [$found[1] . '-data']);
			});
		}
		
		public function get() {
			//			do_action('template_redirect');
			$template = locate_template(array_filter($this->templates));
			$filtered = apply_filters('template_include', apply_filters('virtual_page_template', $template));
			if (empty($filtered) || file_exists($filtered)) {
				$template = $filtered;
			}
			if (!empty($template) && file_exists($template)) {
				require_once $template;
			}
		}
		
		public function post() {
			wp_redirect(home_url($this->route->getUrl()));
			exit;
		}
		
	}
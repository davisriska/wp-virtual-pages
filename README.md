# Wordpress virtual routes

### How to use
1. Add an action to wp_virtual_routes that recieves RouterInterface as a parameter.
2. Add new route with $router->addRoute(); As a param pass RouteInterface type.
    * new Route(); 
        * First param is route url. Example: '/users/login'
        * Second param is of type RouteHandlerInterface. You can extend the RequestHandler class to create your own handler or just pass in RequestHandler 
        with a defined template param to create a simple page that renders your specified template.
        * Third is page title. Example: 'User login form'
```
add_action('wp_virtual_routes', function (RouterInterface $router) {
    $router->addRoute(new Route('/users/login', new LoginHandler(), 'User login'));
    $router->addRoute(new Route('/users/profile', new RequestHandler('views/user-profile.blade.php'), 'User profile'));
});```
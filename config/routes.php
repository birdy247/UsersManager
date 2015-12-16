<?php
use Cake\Routing\Router;

Router::plugin('UsersManager', function ($routes) {
    $routes->fallbacks('DashedRoute');
});

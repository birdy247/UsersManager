<?php
use Cake\Routing\Router;


Router::plugin('UsersManager', ['path' => '/users'], function ($routes) {
    $routes->fallbacks('DashedRoute');
});

Router::connect('/logout', ['plugin' => 'UsersManager', 'controller' => 'Users', 'action' => 'logout']);

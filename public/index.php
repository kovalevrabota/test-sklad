<?php
require dirname(__DIR__) . '/autoload.php';

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

session_start();

/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('login', ['controller' => 'Authorization', 'action' => 'auth']);
$router->add('logout', ['controller' => 'Authorization', 'action' => 'out']);
    
$router->dispatch($_SERVER['REQUEST_URI']);
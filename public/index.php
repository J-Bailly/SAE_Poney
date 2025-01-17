<?php
require_once '../src/App/Config/Autoloader.php';

use App\Controllers\HomeController;

Autoloader::register();

// Récupère l'URI actuelle
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$uri = strtok($uri, '?');

// Charge les routes
$routes = require '../src/App/Config/routes.php';

if (array_key_exists($uri, $routes)) {
    [$controller, $method] = $routes[$uri];
    $controllerInstance = new $controller();
    echo $controllerInstance->$method();
} else {
    http_response_code(404);
    echo 'Page introuvable.';
}

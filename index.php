<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Router;
use App\Handler\Login;
use App\Handler\Map;

$router = new Router();

$router->get('/', Login::class . '::execute');

$router->post('/map', Map::class . '::execute');
$router->get('/map', Map::class . '::execute');


$router->addNotFoundHandler(function (){
    require_once __DIR__ . "/src/view/404.phtml";
});

$router->run();


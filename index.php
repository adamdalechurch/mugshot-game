<?php
namespace MugshotGame;

use SimplePHP\Core\Data\Migration;
use SimplePHP\Core\Infrastructure\Router;
use SimplePHP\Core\Models\Route;

// load constants from .env
require_once('core/config.php');

// load composer
require_once('vendor/autoload.php');

$migration = new Migration();

// load view basic:
// include_once('views/example.php');

// // use router:
$router = new Router();

// // add example route:
$router->add_route(new Route('example', 'ExampleController', 'example', '/example'));

$router->dispatch();

?>
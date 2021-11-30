<?php
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './middlewares/AutentificadorJWT.php';
require_once './db/AccesoDatos.php';
require_once './middlewares/MWComanda.php';


// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// peticiones
$app->group('/empleado', function (RouteCollectorProxy $group) {
  $group->post('/login',\UsuarioController::class . ':LoginEmpleado');
  $group->get('/listados', \UsuarioController::class . ':TraerTodos')->add(\MWComanda::class . ':ValidarSocio')->add(\MWComanda::class . ':ValidarToken');
  $group->post('/alta', \UsuarioController::class . ':CargarUno')->add(\MWComanda::class . ':ValidarSocio')->add(\MWComanda::class . ':ValidarToken');

  $group->post('/altaEmpleadocsv', \UsuarioController::class . ':AltaPorCsv');
  $group->get('/listadoscsv', \UsuarioController::class . ':Mostrarcsv');
 
});

$app->group('/producto', function (RouteCollectorProxy $group) {
  $group->post('/alta', \ProductoController::class . ':CargarUno');
  $group->get('/listados', \ProductoController::class . ':TraerTodos') ;
  
});

$app->group('/mesa', function (RouteCollectorProxy $group) {
$group->get('/listados', \MesaController::class . ':TraerTodos')->add(\MWComanda::class . ':ValidarSocio')->add(\MWComanda::class . ':ValidarToken');
$group->post('/alta', \MesaController::class . ':CargarUno');
$group->post('/estado/comiendo', \MesaController::class . ':cambiarEstadoComiendo')->add(\MWComanda::class . ':ValidarMozo')->add(\MWComanda::class . ':ValidarToken');
$group->post('/estado/pagando', \MesaController::class . ':cambiarEstadoPagando')->add(\MWComanda::class . ':ValidarMozo')->add(\MWComanda::class . ':ValidarToken');
$group->post('/estado/cerrado', \MesaController::class . ':cambiarEstadoCerrado')->add(\MWComanda::class . ':ValidarSocio')->add(\MWComanda::class . ':ValidarToken');
$group->get('/masUsado', \MesaController::class . ':BuscarMesaMasUsada')->add(\MWComanda::class . ':ValidarSocio')->add(\MWComanda::class . ':ValidarToken');

});
$app->group('/pedidos', function (RouteCollectorProxy $group) {

$group->get('/listados', \PedidoController::class . ':TraerTodos')->add(\MWComanda::class . ':ValidarSocio')->add(\MWComanda::class . ':ValidarToken');
$group->get('/pendientes', \PedidoController::class . ':TraerPedidoPendiente')->add(\MWComanda::class . ':ValidarToken');
$group->post('/alta', \PedidoController::class . ':CargarUno')->add(\MWComanda::class . ':ValidarMozo')->add(\MWComanda::class . ':ValidarToken');
$group->post('/tomarPedido', \PedidoController::class . ':tomarPedido')->add(\MWComanda::class . ':ValidarToken');
$group->post('/VerMiPedido', \PedidoController::class . ':TraerTodosPorParametro');
$group->post('/servir', \PedidoController::class . ':ServirPedido')->add(\MWComanda::class . ':ValidarToken');

});

$app->post('/encuesta', \MesaController::class . ':RegistrarEncuesta'); 
$app->get('/comentarios/mejores', \MesaController::class . ':MejoresComentarios')->add(\MWComanda::class . ':ValidarSocio')->add(\MWComanda::class . ':ValidarToken');


// Run app
$app->run();


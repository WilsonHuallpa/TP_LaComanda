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
require_once './middlewares/Credencial.php';
require_once './db/AccesoDatos.php';
require_once './middlewares/AutentificadorJWT.php';
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
$app->group('/login', function (RouteCollectorProxy $group){
  $group->post('/',\UsuarioController::class . ':LoginEmpleado');

});
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('/listados', \UsuarioController::class . ':TraerTodos');
    $group->post('/altaEmpleado', \UsuarioController::class . ':CargarUno')->add(\MWComanda::class . ':ValidarSocio')->add(\MWComanda::class . ':ValidarToken');
    $group->post('/altaEmpleadocsv', \UsuarioController::class . ':AltaPorCsv');
    $group->get('/listadoscsv', \UsuarioController::class . ':Mostrarcsv');
    /*
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->put('[/]', \UsuarioController::class . ':ModificarUno');
    $group->delete('/{id}', \UsuarioController::class . ':BorrarUno');
    ->add(\MWComanda::class . ':ValidarSocio')*/
});
//->add(\Credenciales::class . ":VerificadorDeCredenciales");

$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/pendientes]', \ProductoController::class . ':TraerTodos') ;
    $group->post('[/]', \ProductoController::class . ':CargarUno');
    /*
    $group->get('/{producto}', \ProductoController::class . ':TraerUno');
    $group->put('[/]', \ProductoController::class . ':ModificarUno');
    $group->delete('/{id}', \ProductoController::class . ':BorrarUno');*/
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos');
  $group->post('[/]', \MesaController::class . ':CargarUno');
/*
  $group->get('/{mesa}', \MesaController::class . ':TraerUno');
  $group->put('[/]', \MesaController::class . ':ModificarUno');
  $group->delete('/{id}', \MesaController::class . ':BorrarUno');*/
});
$app->group('/pedidos', function (RouteCollectorProxy $group) {

  $group->get('/listados', \PedidoController::class . ':TraerTodos');
  $group->get('/pendientes', \PedidoController::class . ':TraerPedidoPendiente')->add(\MWComanda::class . ':ValidarToken');
  $group->post('/alta', \PedidoController::class . ':CargarUno')->add(\MWComanda::class . ':ValidarMozo')->add(\MWComanda::class . ':ValidarToken');
 
  /*
  $group->get('/{pedido}', \PedidoController::class . ':TraerUno');
  $group->put('[/]', \PedidoController::class . ':ModificarUno');
  $group->delete('/{id}', \PedidoController::class . ':BorrarUno');*/
});





 // $this->post('/',empleado::class.':ModificarUno')->add(MWComanda::class.':MWValidarAlta')->add(MWComanda::class.':MWValidarIdExistente');

  // JWT test routes
  /*
$app->group('/jwt', function (RouteCollectorProxy $group) {

  $group->post('/crearToken', function (Request $request, Response $response) {    
    $parametros = $request->getParsedBody();

    $usuario = $parametros['usuario'];
    $perfil = $parametros['perfil'];
    $alias = $parametros['alias'];

    $datos = array('usuario' => $usuario, 'perfil' => $perfil, 'alias' => $alias);

    $token = AutentificadorJWT::CrearToken($datos);
    $payload = json_encode(array('jwt' => $token));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  });

  $group->get('/devolverPayLoad', function (Request $request, Response $response) {
    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);

    try {
      $payload = json_encode(array('payload' => AutentificadorJWT::ObtenerPayLoad($token)));
    } catch (Exception $e) {
      $payload = json_encode(array('error' => $e->getMessage()));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  });

  $group->get('/devolverDatos', function (Request $request, Response $response) {
    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);

    try {
      $payload = json_encode(array('datos' => AutentificadorJWT::ObtenerData($token)));
    } catch (Exception $e) {
      $payload = json_encode(array('error' => $e->getMessage()));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  });

  $group->get('/verificarToken', function (Request $request, Response $response) {
    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);
    $esValido = false;

    try {
      AutentificadorJWT::verificarToken($token);
      $esValido = true;
    } catch (Exception $e) {
      $payload = json_encode(array('error' => $e->getMessage()));
    }

    if ($esValido) {
      $payload = json_encode(array('valid' => $esValido));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  });
});*/


  
// Run app
$app->run();


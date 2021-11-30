<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;



class MWComanda 
{


    public function ValidarSocio(Request $request, RequestHandler $handler)
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
    
        try {
          $payload =  AutentificadorJWT::ObtenerData($token);
        
          if($payload->perfil == 1){
            $APi = $handler->handle($request);
        
            $contenidoAPI = (string)$APi->getBody(); 
          }else{
            throw new Exception('error.. en validacion');
          }
        } catch (Exception $e) {
          $contenidoAPI = json_encode(array('error' => $e->getMessage()));
        }
        $response = new ResponseMW();
        $response->getBody()->write($contenidoAPI);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }




    
    public function ValidarToken(Request $request, RequestHandler $handler)
    {
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

            $response = $handler->handle($request);
            $payload = (string)$response->getBody();
        }
        $response = new ResponseMW();
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function ValidarMozo(Request $request, RequestHandler $handler)
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
    
        try {
          $payload =  AutentificadorJWT::ObtenerData($token);
        
          if($payload->perfil == 2){
            $APi = $handler->handle($request);
        
            $contenidoAPI = (string)$APi->getBody(); 
          }else{
            throw new Exception('error.. en validacion');
          }
        } catch (Exception $e) {
          $contenidoAPI = json_encode(array('error' => $e->getMessage()));
        }
        $response = new ResponseMW();
        $response->getBody()->write($contenidoAPI);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

  public static function SumarOperacion(Request $request, RequestHandler $handler) {
    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);
    $payload =  AutentificadorJWT::ObtenerData($token);

    $nombre_operacion = $_SERVER["REQUEST_URI"];
    date_default_timezone_set("America/Argentina/Buenos_Aires");
    $fecha = date('Y-m-d');
    
    try {
        $operacion = new Operacion;        
        $operacion->id_empleado = $payload->id;
        $operacion->operacion = $nombre_operacion;
        $operacion->fecha = $fecha;
        $operacion->GuardarBD();
        $APi = $handler->handle($request);
        $contenidoAPI = (string)$APi->getBody(); 
    }
    catch(Exception $e) {
      $contenidoAPI = json_encode(array('error' => $e->getMessage()));
    } 
    $response = new ResponseMW();
    $response->getBody()->write($contenidoAPI);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}


?>
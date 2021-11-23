<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;



class MWCredenciales
{

    public function VerificadorRol(Request $request, RequestHandler $handler)
    {
        
        if($request->getMethod() === "GET" ){
            $antes = "<p>NO necesita credenciales para los get</p>";

            $response = $handler->handle($request);
            $contenido = (string) $response->getbody();
            $response = new ResponseMW();

            $response->getBody()->write("{$antes} {$contenido}");
            //$response = $handler->handle($request);

        }else if($request->getMethod() === "POST"){
            $response = new ResponseMW();
            $ArrayDeParametros = $request->getParsedBody();
            $nombre=$ArrayDeParametros['nombre'];
            $tipo=$ArrayDeParametros['tipo'];

            if($tipo=="administrador")
            { 
          
              $response->getBody()->write("<h3>Bienvenido $nombre </h3>");
              //$responseMW = $handler->handle($request);
            }
            else
            {
              $response->getBody()->write('<p>no tenes habilitado el ingreso</p>');
            }
        }

        return $response; 
    }
    public function MostrarPerfilesUsuario(Request $request, RequestHandler $handler)
    {
      if($request->getMethod() === "POST");

        $response = new ResponseMW();

        $ArrayDeParametros = $request->getParsedBody();

        $usuario=$ArrayDeParametros['usuario'];
        $mail=$ArrayDeParametros['mail'];

        $UnUsuario = Usuario::obtenerUsuarioMail($usuario, $mail);

        $tipo = $UnUsuario->id_tipo_empleado;
        switch ($tipo) {
          case '1':
            $response->getBody()->write("<h3>Bienvenido socio </h3>");
            break;
          case '2':
            $response->getBody()->write("<h3>Bienvenido mozo </h3>");
          break;
          case '3':
            $response->getBody()->write("<h3>Bienvenido cocinero </h3>");
          break;
          case '4':
            $response->getBody()->write("<h3>Bienvenido bartender </h3>");
          break;
          case '5':
            $response->getBody()->write("<h3>Bienvenido cervecero </h3>");
          break;
          default:
            # code...
            break;
        }

    }


}

?>
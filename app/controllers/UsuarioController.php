<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';


class UsuarioController extends Usuario implements IApiUsable
{

    public function LoginEmpleado($request, $response, $args) {

      try{
        $parametros = $request->getParsedBody();
        $usuario = $parametros['nombre'];
        $clave = $parametros['clave'];

        $usser =  Usuario::VerificarDatos($usuario, $clave);

        $usuario = $usser->nombre;
        $perfil = $usser->id_tipo_empleado;
        $id = $usser->id;
        $datos = array('id'=> $id ,'usuario' => $usuario, 'perfil' => $perfil);

        $token = AutentificadorJWT::CrearToken($datos);
        $payload = json_encode(array('jwt' => $token));

      }catch (Exception $e) {
          $payload = json_encode(array('error' => $e->getMessage()));
      }
  
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }


    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $mail = $parametros['mail'];
        $clave = $parametros['clave'];
        $tipoEmpleado =  strtolower($parametros['tipo_empleado']);
        $id_tipoEmpleado;

        switch($tipoEmpleado) {
            case 'socio':
                $id_tipoEmpleado = 1;
                break;
            case 'mozo':
                $id_tipoEmpleado = 2;
                break;
            case 'cocinero':
                $id_tipoEmpleado = 3;
                break;
            case 'bartender':
                $id_tipoEmpleado = 4;
                break;
            case 'cervecero':
                $id_tipoEmpleado = 5;
                break;
            case 'pastelero':
                $id_tipoEmpleado = 6;
                break;
        }
         $aux = Usuario::obtenerUsuario($nombre);

         if($aux){
            $payload = json_encode(array("mensaje" => "Usuario ya existe"));
         }else{
            $usr = new Usuario();
            $usr->nombre = $nombre;
            $usr->mail = $mail;
            $usr->clave = $clave;
            $usr->id_tipo_empleado = $id_tipoEmpleado;
    
            $usr->crearUsuario();
    
            $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
         }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $usr = $args['usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
          
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();

        $payload = json_encode(array("ListaEmpleados" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        /*$parametros = $request->getParsedBody();

        $usuario =  new Usuario();
        $usuario->id = $parametros['id'];
        $usuario->usuario = $parametros['usuario'];
        $usuario->clave = password_hash($parametros['clave'], PASSWORD_DEFAULT);

        Usuario::modificarUsuario($usuario);

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');*/
    }


    public function BorrarUno($request, $response, $args)
    {

        /*$usuarioId = $args['id'];
        Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');*/
    }

 

    public function AltaPorCsv($request, $response, $args){

      if ( isset($_FILES["archivo"])) {

        if ($_FILES["archivo"]["error"] > 0) {
          echo "Return Code: " . $_FILES["archivo"]["error"] . "<br />";
      
        }
        $tmpName = $_FILES['archivo']['tmp_name'];
        $csv = array_map('str_getcsv', file($tmpName));
        array_walk($csv, function(&$a) use ($csv) {
          $a = array_combine($csv[0], $a);
        });
        array_shift($csv);
       foreach ($csv as $key => $value) {

        $usr = new Usuario();
        $usr->nombre = $value['nombre'];
        $usr->mail = $value['mail'];
        $usr->clave = $value['clave'];
        $usr->id_tipo_empleado = $value['id_tipo_empleado'];

        $usr->crearUsuario();
      }
      $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
      $response->getBody()->write($payload);
      return $response
      ->withHeader('Content-Type', 'application/json');
    }
  }
  public function Mostrarcsv($request, $response, $args){

    $lista = Usuario::obtenerTodoscsv();

    $fp = fopen('Archivos/usuario.csv', 'w');

    foreach ($lista as $val) {
      
      fputcsv($fp, $val);
     }
    fclose($fp);
    //descargar alchivos
    $payload = json_encode(array("mensaje" => "lectura"));
    $response->getBody()->write($payload);
    return $response
    ->withHeader('Content-Type', 'application/json');
  }
  

}
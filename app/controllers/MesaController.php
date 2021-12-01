<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';
require_once './models/Encuesta.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $codigo = $parametros['codigo'];
        $estado = $parametros['id_estado'];

        $aux = Mesa::obtenerMesa($codigo);
        if($aux){
          $payload = json_encode(array("mensaje" => "Mesa ya existe"));
        }else{
          $mesa = new Mesa();
          $mesa->codigo = $codigo;
          $mesa->id_estado = $estado;
          $mesa->crearMesa();
  
          $payload = json_encode(array("mensaje" => "mesa creado con exito"));
        }

    

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
      /*
        // Buscamos usuario por nombre
        $usr = $args['usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');*/
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodos();

        $payload = json_encode(array("listaMesas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
      /*
        $parametros = $request->getParsedBody();

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

      /*
        $usuarioId = $args['id'];
        Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');*/
    }

    public function cambiarEstadoComiendo($request, $response, $args)
    {  
      
      $parametros = $request->getParsedBody();
      $codigo = $parametros['codigo'];
      $numeroPedido = $parametros['pedido'];
      try {

          
          $mesaComiendo = Mesa::obenerMesa($codigo); 

          $pedido = Pedido::TraerUnPedido($numeroPedido);

          if($mesaComiendo === NULL || $pedido === NULL){
            throw new Exception(' MESA  o CODIGO PEDIDO no exiten.');
          }

          if($mesaComiendo->id_estado == 1){

            if($pedido->id_estado_pedido == 3){
              $mesaComiendo->id_estado = 2;
              $mesaComiendo->modificarBD();
              $payload = json_encode(array("mesaje" => "Codigo de pedido: " . $codigo ." comiendo."));
            }else{ 
              $payload = json_encode(array("mesaje" => "su pedido aun no esta listo."));
            }
             
          }else {
            $payload = json_encode(array("mesaje" => "mesa ocupada: " . $codigo ."."));
          }


      
      
      }
      catch(Exception $e) {
        $payload = json_encode(array("Estado" => "ERROR", "Mensaje" => $e->getMessage()));
      }       
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
      
       
    }
    public function cambiarEstadoPagando($request, $response, $args)
    {  
      $parametros = $request->getParsedBody();
      $codigo = $parametros['codigo'];
     
      try {
          $pedidoAServir = Pedido::TraerUnPedido($codigo);
          $mesaComiendo = Mesa::obtenerMesa($codigo);   
          if($mesaComiendo->id_estado == 2){
            
            $totalPagar = Pedido::TraerPedidoApagar($mesaComiendo->id);
            $total = $totalPagar[0]["totalApagar"];
            $mesaComiendo->id_estado = 3;
            $mesaComiendo->modificarBD();
            $payload = json_encode(array("mesaje" => "Codigo : ". $codigo . " Total A pagar: " . $total));
          }else{
            $payload = json_encode(array("mesaje" => "Codigo : ". $codigo . " Ya pagaron"));
          }
      
      }
      catch(Exception $e) {
        $payload = json_encode(array("Estado" => "ERROR", "Mensaje" => $e->getMessage()));
      }       
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    
    }

    public function cambiarEstadoCerrado($request, $response, $args)
    {  
      $parametros = $request->getParsedBody();
      $codigo_mesa = $parametros['codigoMesa'];

     
      try {
        
          $mesaComiendo = Mesa::obtenerMesa($codigo_mesa);   
          if( $mesaComiendo->id_estado != 4){
            $mesaComiendo->id_estado = 4;
            $mesaComiendo->modificarBD();
            $payload = json_encode(array("mesaje" => "Codigo de pedido: " . $codigo_mesa ." cerrado."));
          }else {
            $payload = json_encode(array("mesaje" => "Codigo de pedido: " . $codigo_mesa ." YA se encuentra cerrado."));
          }  
      
      }
      catch(Exception $e) {
        $payload = json_encode(array("Estado" => "ERROR", "Mensaje" => $e->getMessage()));
      }       
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    
    }

    public function RegistrarEncuesta($request, $response, $args) {
      $parametros = $request->getParsedBody();

      $codigoMesa = $parametros['codigo_mesa'];
      $puntuacion = $parametros['puntacion'];
      $comentario = $parametros['comentario'];
      try {
        if(strlen($comentario) > 66) {
            $payload = json_encode(array("mesaje" => "El comentario debe de ser mas de 66 caracteres."));
        }

        $mesaActual =  Mesa::obtenerMesa($codigoMesa);

        if($mesaActual != null) {
          
              $encuesta =  new Encuesta();
              $fecha = date('Y-m-d');
              $encuesta->codigoMesa = $codigoMesa;
              $encuesta->fecha = $fecha;
              $encuesta->puntuacion = $puntuacion;
              $encuesta->comentario = $comentario;
              $encuesta->crearEncuesta();
              $payload = json_encode(array("mesaje" => "Se guardo la encuesta correctamente."));
        }else {
          $payload = json_encode(array("Error" => "Se codigo de mesa no registrada."));
        }  
      }catch(Exception $e) {
        $payload = json_encode(array("Estado" => "ERROR", "Mensaje" => $e->getMessage()));
      }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }

  public function MejoresComentarios($request, $response, $args) {
   
        $lista = Encuesta::ObtenerMejoresComentario();

        $payload = json_encode(array("Mejores comentarios" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    
  }

  public function BuscarMesaMasUsada($request, $response, $args) {
   
    $lista = Mesa::ObtenerCantidadDeMesausada();

    $max;
    $codigoMasUsado;
    foreach ($lista as $key => $value) {
      if($key !== 0){
        if($max < $value['cantidad']){
          $max = $value['cantidad'];
          $codigoMasUsado = $value['codigo'];
        }
      }else{
        $max = $value['cantidad'];
        $codigoMasUsado = $value['codigo'];
      }
    }
    

    $payload = json_encode(array( "mesaje" => "Mesa mas usada: " . $codigoMasUsado . " cantidad de veces: " . $max));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  

 
}
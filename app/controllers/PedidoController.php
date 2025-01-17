<?php

require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';
require_once './models/Pdf.php';
class PedidoController extends Pedido implements IApiUsable {

    public function CargarUno($request, $response, $args) {

   
      try {

        $parametros = $request->getParsedBody();
        $id_mesa  = $parametros['id_mesa'];
        $id_producto = $parametros['id_producto'];
        $cantidad = $parametros['cantidad'];
        $cliente = $parametros['cliente'];
        $codigo = $parametros['codigo'];
        $id_estado_pedido = 1;
        $fecha = date("Y/m/d");
        $dir_subida = 'FotosPedidos/';

        $pedido = new Pedido();
        $pedido->id_mesa = $id_mesa;
        $pedido->id_producto = $id_producto;
        $pedido->cantidad = $cantidad;
        $pedido->cliente = $cliente;
        $pedido->codigo = $codigo;
        $pedido->id_estado_pedido = $id_estado_pedido;
        $pedido->id_empleado = 0;
        $pedido->fecha = $fecha;
        $pedido->tiempo = 0;


        if($_FILES){
          Archivo::VerificarTipoImagen($_FILES["foto"]);
          $nombreImagen =  Archivo::SubirAchivo($dir_subida,$_FILES["foto"],$codigo,$cliente);
          $pedido->nombre_foto = $nombreImagen;
        
        }else{
          throw new Exception('¡Debe de colocar una foto!');
        }

        $pedido->crearPedido();
        $payload = json_encode(array("mensaje" => "El pedido se registró correctamente", "Código de pedido" => $codigo));
  
      }catch(Exception $e) {
          $payload = json_encode(array("Estado" => "ERROR", "Mensaje" => $e->getMessage()));
      }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {/*
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
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listasPedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPedidoPendiente($request, $response, $args){

      $header = $request->getHeaderLine('Authorization');
      $token = trim(explode("Bearer", $header)[1]);

      $payload =  AutentificadorJWT::ObtenerData($token);

      $sector = $payload->perfil;
      
      switch ($payload->perfil) {
        case '3':
          $lista = Pedido::ListaDePendiente(3);
          $payload = json_encode(array("Cocina" => $lista));
          break;

        case '4':
          $lista = Pedido::ListaDePendiente(4);
          $payload = json_encode(array("Barra de Tragos y Vinos" => $lista));
          break;
        case '5';
          $lista = Pedido::ListaDePendiente(5);
          $payload = json_encode(array("Barra de Cervezas" => $lista));
          break;
        case '5';
          $lista = Pedido::ListaDePendiente(6);
          $payload = json_encode(array("Candy Bar" => $lista));
          break;
        default:
          $payload = json_encode(array("Mesaje" => "Solo pueden ingresar los empleados de cocina, Barra de tragos o Cerveza o Candy Bar."));
          break;
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');

    }


    public function tomarPedido($request, $response, $args){

      $header = $request->getHeaderLine('Authorization');
      $token = trim(explode("Bearer", $header)[1]);
    

      try{  
        $parametros = $request->getParsedBody();
        $payload =  AutentificadorJWT::ObtenerData($token);

        $codigo  = $parametros['codigo'];
        $tiempo = $parametros['tiempo'];
        $id_empleado = $payload->id;

        $TomarPedido = Pedido::TraerUnPedido($codigo);

        if($TomarPedido){
          if($TomarPedido->id_estado_pedido == 1){
            $TomarPedido->id_estado_pedido = 2;
            $TomarPedido->tiempo = $tiempo;
            $TomarPedido->id_empleado = $id_empleado;
            $TomarPedido->modificarPedido();
            $payload = json_encode(array("mesaje" => "Codigo de pedido: " . $codigo ."  En preparacion"));
          }else{
            $payload = json_encode(array("mesaje" => "Pedido en preparacion"));
          }
        
  
        }else{
          $payload = json_encode(array("mesaje" => "No existe el codigo, por favor ingrese el codigo correcto."));

        }
        
      }catch(Exception $e) {
        $payload = json_encode(array("Estado" => "ERROR", "Mensaje" => $e->getMessage()));
      }
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');

    }

    public function TraerTodosPorParametro($request, $response, $args) {

      $parametros = $request->getParsedBody();

      $codigo = $parametros['codigo'];
      $numeroPedido = $parametros['numero'];
  
      $lista = Pedido::ObtenerTiempoDePedido($codigo, $numeroPedido);
        $payload = json_encode(array("mensaje" => $lista));

        //chequear
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ServirPedido($request, $response, $args) {

      $parametros = $request->getParsedBody();
      $header = $request->getHeaderLine('Authorization');
      $token = trim(explode("Bearer", $header)[1]);

      $payload =  AutentificadorJWT::ObtenerData($token);

      $id_empleado = $payload->id;
     
      try {
          $pedidoAServir = Pedido::TraerUnPedidoId($id_empleado);   
          if($pedidoAServir->id_estado_pedido == 2){
            $pedidoAServir->id_estado_pedido = 3;
            $pedidoAServir->modificarPedido();
            $respuestaApi = json_encode(array("mesaje" => "Codigo de pedido: " . $pedidoAServir->codigo . " Listo para servir."));
          }else {
            $respuestaApi = json_encode(array("mesaje" => "Codigo de pedido: " . $pedidoAServir->codigo . " no esta lista para servir."));
          }
        
      }
      catch(Exception $e) {
        $respuestaApi = json_encode(array("Estado" => "ERROR", "Mensaje" => $e->getMessage()));
      }       
      $response->getBody()->write($respuestaApi);
      return $response->withHeader('Content-Type', 'application/json');
  }

    

    public function ModificarUno($request, $response, $args)
    {
    }


    public function BorrarUno($request, $response, $args)
    {

    }

    public function DescargarPDFMayorImpor($request, $response, $args){

      $lista = Pedido::obtenerTodosParaPDF();
      $pdf = new PDF();
      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Times','',16);

      foreach ($lista as $key => $row) {
        $pdf->cell(30,10,$row['fecha'],1,0,'C',0);
        $pdf->cell(40,10,$row['producto'],1,0,'C',0);
        $pdf->cell(30,10,$row['cantidad'],1,1,'C',0);
      }
  
      $pdf->Output('Archivos/pedidos.pdf', 'F');
      $payload = json_encode(array("lista de pedidos" => $lista));
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }
}
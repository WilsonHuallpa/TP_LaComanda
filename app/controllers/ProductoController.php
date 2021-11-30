<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
  
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $sector = $parametros['id_sector'];
        $nombre = $parametros['nombre'];
        $precio = $parametros['precio'];
        $stock = $parametros['stock'];
        
        $prod = new Producto();
        $prod->id_sector = $sector;
        $prod->nombre = $nombre;
        $prod->precio = $precio;
        $prod->stock = $stock;
        $prod->crearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
  
    }
    public function TraerTodos($request, $response, $args)
    {
          $lista = Producto::obtenerTodos();
          $payload = json_encode(array("listaProductos" => $lista));
  
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
/*
    public function Login($request, $response, $args) {

      $parametros = $request->getParsedBody();
      $usuario = $parametros['usuario'];
      $clave = $parametros['clave'];

      $retono =  Usuario::VerificarDatos($usuario, $clave);
      $payload = json_encode(array("mensaje" => $retono));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    */
}
<?php

class Pedido
{
    public $id;
    public $id_mesa;
    public $id_producto;
    public $cantidad;
    public $cliente;
    public $codigo;
    public $id_estado_pedido;
    public $fecha;
    public $id_empleado;
    public $id_estado_mesa;
    public $nombre_foto;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (id_mesa, id_producto, cantidad, cliente, codigo, id_estado_pedido, fecha, id_empleado, id_estado_mesa ,nombre_foto ) VALUES (:id_mesa, :id_producto, :cantidad, :cliente, :codigo, :id_estado_pedido, :fecha, :id_empleado, :id_estado_mesa, :nombre_foto)");

        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':id_producto', $this->id_producto, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':cliente', $this->cliente, PDO::PARAM_STR);
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':id_estado_pedido', $this->id_estado_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':id_empleado', $this->id_empleado, PDO::PARAM_INT);
        $consulta->bindValue(':id_estado_mesa', $this->id_estado_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':nombre_foto', $this->nombre_foto, PDO::PARAM_STR);
       
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, id_mesa, id_producto, cantidad, cliente, codigo, id_estado_pedido,  fecha, id_empleado, id_estado_mesa, nombre_foto FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
  
    public static function ListaDePendiente($sector){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT productos.nombre, pedidos.cantidad FROM productos INNER JOIN pedidos ON pedidos.id_estado_pedido = 1 AND pedidos.id_producto = productos.id AND productos.id_sector = :sector");
        $consulta->bindValue(':sector', $sector, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
        
    }

    public function SubirAchivo($ruta, $file) {

        $nombre_archivo = $this->codigo ."-". $this->id_mesa ;
        $fichero_subido = $ruta . $nombre_archivo . ".jpg";
     
        if (!file_exists($ruta)){
            mkdir($ruta, 0777, true);
        }
        if (move_uploaded_file($file['tmp_name'], $fichero_subido)) {
                $this->nombre_foto = $fichero_subido;
                return true; 
        }else{
            throw new Exception('¡Posible ataque de subida de ficheros!');
        }
   }

  


/*
    public static function obtenerUsuario($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, clave FROM usuario WHERE usuario = :usuario");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function modificarUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuario SET usuario = :usuario, clave = :clave WHERE id = :id");

        $consulta->bindValue(':usuario', $usuario->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $usuario->clave, PDO::PARAM_STR);
        $consulta->bindValue(':id', $usuario->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuario SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }

    public static function VerificarDatos($usuario, $clave){

        $retono;
        $usser = usuario::obtenerUsuario($usuario);
        if($usser != false){
            if(password_verify($clave, $usser->clave)){
                $retono = "se encontro el usuario:" . " " . $usser->usuario;
            }else{
                $retono = "Incorrecta la contraseña";
            }
        }
        else {
                $retono = "no se encontro el usuario:";
        }
        return $retono;
    }*/
}
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
    public $nombre_foto;
    public $tiempo;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (id_mesa, id_producto, cantidad, cliente, codigo, id_estado_pedido, fecha, id_empleado,nombre_foto , tiempo ) VALUES (:id_mesa, :id_producto, :cantidad, :cliente, :codigo, :id_estado_pedido, :fecha, :id_empleado, :nombre_foto, :tiempo)");

        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':id_producto', $this->id_producto, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':cliente', $this->cliente, PDO::PARAM_STR);
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':id_estado_pedido', $this->id_estado_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':id_empleado', $this->id_empleado, PDO::PARAM_INT);
        $consulta->bindValue(':nombre_foto', $this->nombre_foto, PDO::PARAM_STR);
        $consulta->bindValue(':tiempo', $this->tiempo, PDO::PARAM_INT);
       
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, id_mesa, id_producto, cantidad, cliente, codigo, id_estado_pedido,  fecha, id_empleado, nombre_foto,tiempo FROM pedidos");
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
    
    public static function ListaDePendienteATomar($sector){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT pedidos.id, id_mesa, id_producto, cantidad, cliente, codigo, id_estado_pedido, fecha, id_empleado, nombre_foto, tiempo FROM productos INNER JOIN pedidos ON pedidos.id_estado_pedido = 1 AND pedidos.id_producto = productos.id AND productos.id_sector = :sector");
        $consulta->bindValue(':sector', $sector, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
        
    }
    public function modificarPedido()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET  id_estado_pedido = :estado , tiempo = :tiempo , id_empleado = :idEmpleado  WHERE id = :id");

        $consulta->bindValue(':estado', $this->id_estado_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':tiempo', $this->tiempo, PDO::PARAM_INT);
        $consulta->bindValue(':idEmpleado', $this->id_empleado, PDO::PARAM_INT);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }

 
    public static function ObtenerTiempoDePedido($codigo , $numero){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT pedidos.codigo, SUM(tiempo) as Minutos FROM pedidos INNER JOIN mesas ON pedidos.id_mesa = mesas.id AND mesas.codigo = ? AND pedidos.codigo = ?");
        $consulta->execute(array($codigo,$numero));
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
        
    }

    public static function TraerUnPedidoId($id){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT  id, id_mesa, id_producto, cantidad, cliente, codigo, id_estado_pedido,  fecha, id_empleado, nombre_foto,tiempo FROM pedidos WHERE id_empleado = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
        return$consulta->fetchObject('Pedido');
        
    }
    public static function TraerUnPedido($codigo){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT  id, id_mesa, id_producto, cantidad, cliente, codigo, id_estado_pedido,  fecha, id_empleado, nombre_foto,tiempo FROM pedidos WHERE codigo = :codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_INT);
        $consulta->execute();
        return$consulta->fetchObject('Pedido');
        
    }
    public static function TraerPedidoApagar($id){

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT pedidos.codigo as codigo, (pedidos.cantidad * productos.precio) as totalApagar FROM pedidos INNER JOIN productos INNER JOIN mesas ON pedidos.id_mesa = ? AND pedidos.id_producto = productos.id AND mesas.id = ?");
       
        $consulta->execute(array($id, $id));
        $consulta->execute();
   
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
     
    }
    public static function obtenerTodosParaPDF()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT pedidos.fecha as fecha, productos.nombre as producto, pedidos.cantidad as cantidad FROM pedidos INNER JOIN productos ON pedidos.id_producto = productos.id ");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
   

  


  
}
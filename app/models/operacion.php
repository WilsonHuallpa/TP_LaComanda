<?php

class Operacion{

    public $id;
    public $id_empleado;
    public $operacion;
    public $fecha;



    
    public function GuardarBD()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO operaciones (id_empleado, operacion, fecha ) VALUES (:id_empleado, :operacion, :fecha)");
        $consulta->bindValue(':id_empleado', $this->id_empleado, PDO::PARAM_INT);
        $consulta->bindValue(':operacion', $this->operacion, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }



}

?>


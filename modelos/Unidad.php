<?php
    require '../config/conexion.php';

    Class Unidad
    {
        public function __construct()
        {

        }

        public function insertar($nombre, $descripcion)
        {
            $sql = "INSERT INTO unidad (nombre,descripcion,condicion) 
                    VALUES ('$nombre','$descripcion','1')";
            
            return ejecutarConsulta($sql);
        }

        public function editar($idunidad, $nombre, $descripcion)
        {
            $sql = "UPDATE unidad SET nombre='$nombre', descripcion='$descripcion'
                    WHERE idunidad='$idunidad'";
            
            return ejecutarConsulta($sql);
        }

        //METODOS PARA ACTIVAR CATEGORIAS
        public function desactivar($idunidad)
        {
            $sql= "UPDATE unidad SET condicion='0' 
                   WHERE idunidad='$idunidad'";
            
            return ejecutarConsulta($sql);
        }

        public function activar($idunidad)
        {
            $sql= "UPDATE unidad SET condicion='1' 
                   WHERE idunidad='$idunidad'";
            
            return ejecutarConsulta($sql);
        }

        //METODO PARA MOSTRAR LOS DATOS DE UN REGISTRO A MODIFICAR
        public function mostrar($idunidad)
        {
            $sql = "SELECT * FROM unidad 
                    WHERE idunidad='$idunidad'";

            return ejecutarConsultaSimpleFila($sql);
        }

        //METODO PARA LISTAR LOS REGISTROS
        public function listar()
        {
            $sql = "SELECT * FROM unidad";

            return ejecutarConsulta($sql);
        }

        //METODO PARA LISTAR LOS REGISTROS Y MOSTRAR EN EL SELECT
        public function select()
        {
            $sql = "SELECT * FROM unidad 
                    WHERE condicion = 1";

            return ejecutarConsulta($sql);
        }
    }

?>
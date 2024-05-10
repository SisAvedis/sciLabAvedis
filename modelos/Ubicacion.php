<?php
    require '../config/conexion.php';

    Class Ubicacion
    {
        public function __construct()
        {

        }

        public function insertar($codigo, $descripcion)
        {
            $sql = "INSERT INTO ubicacion (codigo,descripcion,condicion) 
                    VALUES ('$codigo','$descripcion','1')";
            
            return ejecutarConsulta($sql);
        }

        public function editar($idubicacion, $codigo, $descripcion)
        {
            $sql = "UPDATE ubicacion SET codigo='$codigo', descripcion='$descripcion'
                    WHERE idubicacion='$idubicacion'";
            
            return ejecutarConsulta($sql);
        }

        //METODOS PARA ACTIVAR CATEGORIAS
        public function desactivar($idubicacion)
        {
            $sql= "UPDATE ubicacion SET condicion='0' 
                   WHERE idubicacion='$idubicacion'";
            
            return ejecutarConsulta($sql);
        }

        public function activar($idubicacion)
        {
            $sql= "UPDATE ubicacion SET condicion='1' 
                   WHERE idubicacion='$idubicacion'";
            
            return ejecutarConsulta($sql);
        }

        //METODO PARA MOSTRAR LOS DATOS DE UN REGISTRO A MODIFICAR
        public function mostrar($idubicacion)
        {
            $sql = "SELECT * FROM ubicacion 
                    WHERE idubicacion='$idubicacion'";

            return ejecutarConsultaSimpleFila($sql);
        }

        //METODO PARA LISTAR LOS REGISTROS
        public function listar()
        {
            $sql = "SELECT * FROM ubicacion WHERE condicion = 1";

            return ejecutarConsulta($sql);
        }

        //METODO PARA LISTAR LOS REGISTROS Y MOSTRAR EN EL SELECT
        public function select()
        {
            $sql = "SELECT * FROM ubicacion 
                    WHERE condicion = 1";

            return ejecutarConsulta($sql);
        }
    }

?>
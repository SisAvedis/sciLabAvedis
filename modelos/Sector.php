<?php
    require '../config/conexion.php';

    Class Sector 
    {
        public function __construct()
        {

        }

        public function insertar($nombre, $descripcion)
        {
            $sql = "INSERT INTO sector (nombre,descripcion,condicion) 
                    VALUES ('$nombre','$descripcion','1')";
            
			return ejecutarConsulta($sql);
        }

        public function editar($idSector,$nombre, $descripcion)
        {
            $sql = "UPDATE sector SET nombre='$nombre', descripcion='$descripcion'
                    WHERE idsector='$idSector'";
            
			return ejecutarConsulta($sql);
        }

        //METODOS PARA ACTIVAR CATEGORIAS
        public function desactivar($idSector)
        {
            $sql= "UPDATE sector SET condicion='0' 
                   WHERE idsector='$idSector'";
            
            return ejecutarConsulta($sql);
        }

        public function activar($idSector)
        {
            $sql= "UPDATE sector SET condicion='1' 
                   WHERE idsector='$idSector'";
            
            return ejecutarConsulta($sql);
        }

        //METODO PARA MOSTRAR LOS DATOS DE UN REGISTRO A MODIFICAR
        public function mostrar($idSector)
        {
            $sql = "SELECT * FROM sector 
                    WHERE idsector='$idSector'";

            return ejecutarConsultaSimpleFila($sql);
        }

        //METODO PARA LISTAR LOS REGISTROS
        public function listar()
        {
            $sql = "SELECT * FROM sector";

            return ejecutarConsulta($sql);
        }

        //METODO PARA LISTAR LOS REGISTROS Y MOSTRAR EN EL SELECT
        public function select()
        {
            $sql = "SELECT * FROM sector 
                    WHERE condicion = 1";

            return ejecutarConsulta($sql);
        }
    }

?>
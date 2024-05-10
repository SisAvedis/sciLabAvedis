<?php
    require '../config/conexion.php';

    Class Articulo 
    {
        public function __construct()
        {

        }

        public function insertar($idcategoria,$idunidad,$idusuario,$codigo,$nombre,$cantidad,$descripcion,$imagen)
        {
            $sql = "INSERT INTO 
                        articulo (
                            idcategoria,
							idunidad,
							idusuario,
                            codigo,
                            nombre,
                            cantidad,
                            descripcion,
                            imagen,
                            condicion
                        ) 
                    VALUES (
                        '$idcategoria',
						'$idunidad',
						'$idusuario',
                        '$codigo',
                        '$nombre',
                        '$cantidad',
                        '$descripcion',
                        '$imagen',
                        '1')";
            //echo 'Variable sql -> '.$sql.'</br>';
			return ejecutarConsulta($sql);
        }

        public function editar($idarticulo,$idcategoria,$idunidad,$idusuario,$codigo,$nombre,$descripcion,$imagen)
        {
            $sql = "UPDATE articulo SET 
                    idcategoria ='$idcategoria',
					idunidad ='$idunidad',
                    idusuario ='$idusuario',
					codigo = '$codigo', 
                    nombre = '$nombre', 
                    descripcion = '$descripcion', 
                    imagen = '$imagen' 
                    WHERE idarticulo='$idarticulo'";
            //echo 'Variable sql -> '.$sql.'</br>';
            return ejecutarConsulta($sql);
        }

        //METODOS PARA ACTIVAR ARTICULOS
        public function desactivar($idarticulo)
        {
            $sql= "UPDATE articulo SET condicion='0' 
                   WHERE idarticulo='$idarticulo'";
            
            return ejecutarConsulta($sql);
        }

        public function activar($idarticulo)
        {
            $sql= "UPDATE articulo SET condicion='1' 
                   WHERE idarticulo='$idarticulo'";
            
            return ejecutarConsulta($sql);
        }

        //METODO PARA MOSTRAR LOS DATOS DE UN REGISTRO A MODIFICAR
        public function mostrar($idarticulo)
        {
            $sql = "SELECT 
					a.idarticulo,
					a.idusuario,
					a.nombre as nombre,
					a.idcategoria,
					a.codigo as codigo,
					a.idunidad as unidad,
					a.descripcion as descripcion,
					sa.stock as stock,
					a.imagen as imagen,
					a.condicion
					FROM articulo a
					INNER JOIN stockarticulo sa
					ON a.idarticulo = sa.idarticulo
                    WHERE a.idarticulo='$idarticulo'";

            return ejecutarConsultaSimpleFila($sql);
        }

        //METODO PARA LISTAR LOS REGISTROS
        public function listar()
        {
            $sql = "SELECT 
                    a.idarticulo, 
                    a.idcategoria, 
                    c.nombre as categoria,
                    a.idunidad, 
                    d.nombre as unidad,
					a.codigo,
                    a.nombre,
                    sa.stock,
                    a.descripcion,
                    a.imagen,
                    a.condicion 
                    FROM articulo a 
                    INNER JOIN categoria c 
                    ON a.idcategoria = c.idcategoria
					INNER JOIN unidad d
					ON a.idunidad = d.idunidad
					LEFT JOIN stockarticulo sa
					ON a.idarticulo = sa.idarticulo";

            return ejecutarConsulta($sql);
        }

        //Listar registros activos - _ingresos
        public function listarActivos()
        {
            $sql = "SELECT 
                    a.idarticulo, 
                    a.idcategoria, 
                    c.nombre as categoria,
                    a.idunidad, 
                    d.nombre as unidad,
					a.codigo,
                    a.nombre,
                    sa.stock,
                    a.descripcion,
                    a.imagen,
                    a.condicion 
                    FROM articulo a
					INNER JOIN stockarticulo sa
					ON a.idarticulo = sa.idarticulo
                    INNER JOIN categoria c 
                    ON a.idcategoria = c.idcategoria
                    INNER JOIN unidad d
					ON a.idunidad = d.idunidad
					WHERE a.condicion = '1'";

            return ejecutarConsulta($sql);
        }

        public function listarActivosVenta()
        {
            $sql = "SELECT 
                    a.idarticulo, 
                    a.idcategoria, 
                    c.nombre as categoria,
                    a.codigo,
                    a.nombre,
                    a.stock,
                    (
                        SELECT precio_venta 
                        FROM detalle_ingreso
                        WHERE idarticulo = a.idarticulo
                        ORDER BY iddetalle_ingreso 
                        desc limit 0,1 

                    ) as precio_venta, 
                    a.descripcion,
                    a.imagen,
                    a.condicion
                    FROM articulo a 
                    INNER JOIN categoria c 
                    ON a.idcategoria = c.idcategoria
                    WHERE a.condicion = '1'";

            return ejecutarConsulta($sql);
        }

		//Listar registros activos
        public function listarActivosUbi()
        {
            $sql = "SELECT 
                    a.idarticulo, 
                    a.idcategoria, 
                    c.nombre as categoria,
                    a.idunidad, 
                    d.nombre as unidad,
					a.codigo,
					i.idubicacion,
					u.codigo AS c_ubi,
                    a.nombre,
                    i.cantidad,
                    a.descripcion,
                    a.imagen,
                    a.condicion 
                    FROM articulo a 
                    INNER JOIN categoria c 
                    ON a.idcategoria = c.idcategoria
                    INNER JOIN unidad d
					ON a.idunidad = d.idunidad
					INNER JOIN inventario i
					ON a.idarticulo = i.idarticulo
					INNER JOIN ubicacion u
					ON i.idubicacion = u.idubicacion
					WHERE a.condicion = '1'
					AND i.idubicacion <>'1'
					AND i.cantidad > 0";

            return ejecutarConsulta($sql);
        }
		
		//Listar artículos por ubicación según el usuario
        public function listarActivosUbiUsuario($idusuario)
        {
            $sql = "CALL pr_listarArticuloUsuario('".$idusuario."')";
			//echo $sql."</br>";
            return ejecutarConsulta($sql);
        }
		
		//Listar artículos por ubicación según el usuario
        public function listarActivosUbiUnoUsuario($idusuario,$idartubi)
        {
            $sql = "CALL prParseArrayv21('".$idusuario."','".$idartubi."')";
			//echo $sql."</br>";
            return ejecutarConsulta($sql);
        }
		
		//Listar registros activos
        public function listarActivosUbiUno($idartubi)
        {
            $sql = "CALL prParseArrayv2('".$idartubi."')";
			//echo $sql."</br>";
            return ejecutarConsulta($sql);
        }

		//Listar registros activos
        public function listarActivosUbiDos($idartubi,$idcliente)
        {
            $sql = "CALL prParseArrayv4('".$idartubi."','".$idcliente."')";
			//echo $sql."</br>";
            return ejecutarConsulta($sql);
        }


		//Listar registros activos
        public function listarActivosExt($idpersona)
        {
            $sql = "CALL pr_listarArticuloDevolucion('".$idpersona."')";
			//echo $sql."</br>";
            return ejecutarConsulta($sql);
        }

		//METODO PARA LISTAR LOS REGISTROS
        public function listarSimple()
        {
            $sql = "SELECT idarticulo,codigo,nombre, descripcion FROM articulo";

            return ejecutarConsulta($sql);
        }

    }

?>
<?php
    require '../config/conexion.php';

    Class Movimiento 
    {
        public function __construct()
        {

        }

        public function insertar($idusuario,$tipo_comprobante,$ubi_origen,$ubi_destino,$idarticulo,$cantidad)
        {
            
			$sql = "SELECT valor FROM numerador_serie WHERE idnumerador = 1";
            //Devuelve serie comprobante
			$serie_comprobante =  ejecutarConsultaSimpleFila($sql);
			$sql = "SELECT valor + 1 AS valor FROM numerador_comprobante WHERE idnumerador = 1";
            //Devuelve número comprobante
			$num_comprobante =  ejecutarConsultaSimpleFila($sql);
			
			$sql = "SELECT NOW() AS fechahora";
			//Devuelve fecha actual
			$fecha_hora = ejecutarConsultaSimpleFila($sql);
			
			$sql = "INSERT INTO movimiento (
                        idusuario,
                        tipo_comprobante,
                        serie_comprobante,
                        num_comprobante,
                        fecha_hora,
                        estado
                    ) 
                    VALUES (
                        '$idusuario',
						'$tipo_comprobante',
                        '$serie_comprobante[valor]',
                        '$num_comprobante[valor]',
                        '$fecha_hora[fechahora]',
                        'Aceptado'
                        )";
            
            //Devuelve id registrado
            $idmovimientonew = ejecutarConsulta_retornarID($sql);
			//echo 'Variable sql -> '.$sql.'</br>';
			//echo 'Variable idmovimientonew -> '.$idmovimientonew.'</br>';
            
			$sql= "UPDATE movimiento SET idtipo_comprobante='$idmovimientonew' 
                   WHERE idmovimiento='$idmovimientonew'";
            //echo 'Variable sql -> '.$sql.'</br>';
            ejecutarConsulta($sql) or $sw = false;

			$sql= "UPDATE numerador_comprobante SET valor='$num_comprobante[valor]' 
                   WHERE idnumerador=1";
            //echo 'Variable sql -> '.$sql.'</br>';
            ejecutarConsulta($sql) or $sw = false;

			$num_elementos = 0;
            $sw = true;

            while($num_elementos < count($idarticulo))
            {
                $sql_detalle ="INSERT INTO detalle_movimiento (
                                    idmovimiento,
                                    idubi_origen,
									idubi_destino,
									idarticulo,
                                    cantidad
                                )
                                VALUES (
                                    '$idmovimientonew',
                                    '$ubi_origen[$num_elementos]',
									'$ubi_destino[$num_elementos]',
									'$idarticulo[$num_elementos]',
                                    '$cantidad[$num_elementos]'
                                )";
				//echo 'Variable sql_detalle -> '.$sql_detalle.'</br>';
                ejecutarConsulta($sql_detalle) or $sw = false;

                $num_elementos = $num_elementos + 1;
            }

            return $sw;
        }
		
		public function validartipocomprobante($idmovimiento)
        {
            $sql= "SELECT tipo_comprobante AS tipoc FROM movimiento  
                   WHERE idmovimiento='$idmovimiento' AND estado = 'Aceptado'";
            
            return ejecutarConsultaSimpleFila($sql);
        }
		
		public function validar($idmovimiento)
        {
            $sql = "CALL pr_validarExistenciaMovimiento('".$idmovimiento."')";
            //echo $sql."</br>";
            return ejecutarConsultaSimpleFila($sql);
        }
		
        public function anular($idmovimiento)
        {
            $sql = "CALL pr_updMovimiento('".$idmovimiento."')";
            
			return ejecutarConsulta($sql);
        }
    
        public function mostrar($idmovimiento)
        {
            $sql = "CALL pr_verMovimiento('".$idmovimiento."')";
			
			return ejecutarConsultaSimpleFila($sql);
        }

        public function listarDetalle($idmovimiento)
        {
            $sql = "SELECT mv.iddetalle_movimiento,mv.idarticulo,a.codigo,a.nombre,a.descripcion,mv.cantidad,uo.codigo AS cod_origen,ud.codigo AS cod_destino
				FROM detalle_movimiento mv 
				INNER JOIN articulo a 
				ON mv.idarticulo=a.idarticulo 
				INNER JOIN ubicacion uo
				ON mv.idubi_origen = uo.idubicacion
				INNER JOIN ubicacion ud
				ON mv.idubi_destino = ud.idubicacion
				where mv.idmovimiento='$idmovimiento'";
			//echo 'Variable sql -> '.$sql.'</br>';
            return ejecutarConsulta($sql);
        }

        public function listar()
        {
            $sql = "SELECT m.idmovimiento,DATE_FORMAT(m.fecha_hora,'%Y-%m-%d') as fecha,u.idusuario,u.nombre as usuario,
			tc.descripcion AS tipo_comprobante,	m.serie_comprobante,m.num_comprobante, m.estado 
					FROM movimiento m
                    INNER JOIN usuario u 
					ON m.idusuario = u.idusuario
                    INNER JOIN tipo_comprobante tc
					ON m.tipo_comprobante = tc.idtipo_comprobante
					";

            return ejecutarConsulta($sql);
        }

		 //METODO PARA LISTAR LOS REGISTROS Y MOSTRAR EN EL SELECT
        public function selectUno()
        {
            $sql = "SELECT * FROM ubicacion WHERE condicion = 1";

            return ejecutarConsulta($sql);
        }
		
		 //METODO PARA LISTAR LOS REGISTROS Y MOSTRAR EN EL SELECT
        public function selectDos()
        {
            $sql = "SELECT * FROM ubicacion WHERE condicion = 1 OR condicion = 3";

            return ejecutarConsulta($sql);
        }

		public function buscarProximo()
        {
            $sql = "CALL pr_verMovimiento('".$idmovimiento."')";
			
			return ejecutarConsultaSimpleFila($sql);
        }
		
    }

?>
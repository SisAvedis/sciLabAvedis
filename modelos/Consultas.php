<?php
    require '../config/conexion.php';

    Class Consultas
    {
        public function __construct()
        {

        }

        public function comprafecha($fecha_inicio, $fecha_fin)
        {
            $sql = "SELECT 
                        DATE_FORMAT(i.fecha_hora,'%d-%m-%Y') as fecha,
                        u.nombre as usuario,
                        p.nombre as proveedor,
                        i.tipo_comprobante,
                        i.serie_comprobante,
                        i.num_comprobante,
                        i.total_compra,
                        i.impuesto,
                        i.estado
                    FROM
                        ingreso i
                    INNER JOIN persona p
                    ON i.idproveedor = p.idpersona
                    INNER JOIN usuario u
                    ON i.idusuario = u.idusuario
                    WHERE 
                        DATE(i.fecha_hora) >= '$fecha_inicio'
                    AND
                        DATE(i.fecha_hora) <= '$fecha_fin'";

            return ejecutarConsulta($sql);
        }

		public function stock()
        {
            $sql = "SELECT 
                        i.idubicacion,
                        a.codigo as cod_art,
                        a.nombre as articulo,
						a.descripcion as descripcion,
                        u.codigo as cod_ubi,
                        u.descripcion as codigo,
                        i.cantidad
                    FROM
                        inventario i
                    INNER JOIN ubicacion u
                    ON i.idubicacion = u.idubicacion
                    INNER JOIN articulo a
                    ON i.idarticulo = a.idarticulo
                    WHERE 
						NOT i.idubicacion IN(1,2,3,4,5,6)
					AND
                        a.condicion = 1";
					// AND i.cantidad > 0";
			//echo $sql.'</br>';
            return ejecutarConsulta($sql);
        }


        public function ventasfechacliente($fecha_inicio, $fecha_fin, $idcliente)
        {
            $sql = "SELECT 
                        DATE_FORMAT(v.fecha_hora,'%d-%m-%Y') as fecha,
                        u.nombre as usuario,
                        p.nombre as cliente,
                        v.tipo_comprobante,
                        v.serie_comprobante,
                        v.num_comprobante,
                        v.estado
                    FROM
                        venta v
                    INNER JOIN persona p
                    ON v.idcliente = p.idpersona
                    INNER JOIN usuario u
                    ON v.idusuario = u.idusuario
                    WHERE 
                        DATE(v.fecha_hora) >= '$fecha_inicio'
                    AND
                        DATE(v.fecha_hora) <= '$fecha_fin'
                    AND
                        v.idcliente = '$idcliente'";

            return ejecutarConsulta($sql);
        }

		
		public function entregasfechaoperario($fecha_inicio, $fecha_fin, $idcliente)
        {
            $sql = "SELECT 
                        DATE_FORMAT(v.fecha_hora,'%d-%m-%Y') as fecha,
                        v.tipo_comprobante,
                        v.serie_comprobante,
                        v.num_comprobante,
                        a.codigo,
						a.nombre,
						a.descripcion,
						dv.cantidad
                    FROM
                        venta v
                    INNER JOIN persona p
                    ON v.idcliente = p.idpersona
                    INNER JOIN detalle_venta dv
                    ON v.idventa = dv.idventa
					INNER JOIN articulo a
					ON dv.idarticulo = a.idarticulo
                    WHERE 
                        DATE(v.fecha_hora) >= '$fecha_inicio'
                    AND
                        DATE(v.fecha_hora) <= '$fecha_fin'
                    AND
                        v.idcliente = '$idcliente'";

            return ejecutarConsulta($sql);
        }


		public function entregasfechaoperarioagrupado($fecha_inicio, $fecha_fin, $idcliente)
        {
            $sql = "SELECT 
                        DATE_FORMAT(v.fecha_hora,'%d-%m-%Y') as fecha,
                        a.codigo,
						a.nombre,
						a.descripcion,
						SUM(dv.cantidad) AS cantidad
                    FROM
                        venta v
                    INNER JOIN persona p
                    ON v.idcliente = p.idpersona
                    INNER JOIN detalle_venta dv
                    ON v.idventa = dv.idventa
					INNER JOIN articulo a
					ON dv.idarticulo = a.idarticulo
                    WHERE 
                        DATE(v.fecha_hora) >= '$fecha_inicio'
                    AND
                        DATE(v.fecha_hora) <= '$fecha_fin'
                    AND
						v.estado = 'Aceptado'
					AND
                        v.idcliente = '$idcliente'
					GROUP BY a.codigo";

            return ejecutarConsulta($sql);
        }
		
		public function devolucionesfechaoperario($fecha_inicio, $fecha_fin, $idcliente)
        {
            $sql = "SELECT 
                        DATE_FORMAT(d.fecha_hora,'%d-%m-%Y') as fecha,
                        d.tipo_comprobante,
                        d.serie_comprobante,
                        d.num_comprobante,
                        a.codigo,
						a.nombre,
						a.descripcion,
						dd.cantidad
                    FROM
                        devolucion d
                    INNER JOIN persona p
                    ON d.idcliente = p.idpersona
                    INNER JOIN detalle_devolucion dd
                    ON d.iddevolucion = dd.iddevolucion
					INNER JOIN articulo a
					ON dd.idarticulo = a.idarticulo
                    WHERE 
                        DATE(d.fecha_hora) >= '$fecha_inicio'
                    AND
                        DATE(d.fecha_hora) <= '$fecha_fin'
                    AND
                        d.idcliente = '$idcliente'";

            return ejecutarConsulta($sql);
        }
		
		
		public function devolucionesfechaoperarioagrupado($fecha_inicio, $fecha_fin, $idcliente)
        {
            $sql = "SELECT 
                        DATE_FORMAT(d.fecha_hora,'%d-%m-%Y') as fecha,
                        a.codigo,
						a.nombre,
						a.descripcion,
						SUM(dd.cantidad) AS cantidad
                    FROM
                        devolucion d
                    INNER JOIN persona p
                    ON d.idcliente = p.idpersona
                    INNER JOIN detalle_devolucion dd
                    ON d.iddevolucion = dd.iddevolucion
					INNER JOIN articulo a
					ON dd.idarticulo = a.idarticulo
                    WHERE 
                        DATE(d.fecha_hora) >= '$fecha_inicio'
                    AND
                        DATE(d.fecha_hora) <= '$fecha_fin'
                    AND
						d.estado = 'Aceptado'
					AND
                        d.idcliente = '$idcliente'
					GROUP BY a.codigo";

            return ejecutarConsulta($sql);
        }
		
		public function pr_movimientosarticulo($fecha_inicio, $fecha_fin, $idarticulo)
        {
            $sql = "CALL pr_movimientosarticulo('".$fecha_inicio."','".$fecha_fin."','".$idarticulo."')";
			
			return ejecutarConsulta($sql);
        }

        public function totalCompraHoy()
        {
            $sql= "SELECT 
                        IFNULL(SUM(total_compra),0) as total_compra
                    FROM
                        ingreso
                    WHERE
                        DATE(fecha_hora) = curdate()";
            
            return ejecutarConsulta($sql);
        }

        public function totalVentaHoy()
        {
            $sql= "SELECT 
                        IFNULL(SUM(total_venta),0) as total_venta
                    FROM
                        venta
                    WHERE
                        DATE(fecha_hora) = curdate()";
            
            return ejecutarConsulta($sql);
        }


        public function comprasUlt10dias()
        {
            $sql= "SELECT 
                        CONCAT(DAY(fecha_hora),'-',MONTH(fecha_hora)) as fecha,
                        SUM(total_compra) as total
                    FROM
                        ingreso
                    GROUP BY
                        fecha_hora 
                    ORDER BY
                        fecha_hora
                    DESC limit 0,10";
            
            return ejecutarConsulta($sql);
        }

        public function ventas12meses()
        {
            $sql= "SELECT 
                        DATE_FORMAT(fecha_hora,'%M') as fecha,
                        SUM(total_venta) as total
                    FROM
                        venta
                    GROUP BY
                        MONTH(fecha_hora) 
                    ORDER BY
                        fecha_hora
                    DESC limit 0,12";
            
            return ejecutarConsulta($sql);
        }

    }

?>
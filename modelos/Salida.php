<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/conexion.php";

Class Venta
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idcliente,$idusuario,$tipo_comprobante,$fecha_hora,$observacion,$impuesto,$total_venta,$idarticulo,$cantidad,$idubi_origen,$idubi_destino)
	{
		
			$sql = "SELECT valor FROM numerador_serie WHERE idnumerador = 2";
            //Devuelve serie comprobante
			$serie_comprobante =  ejecutarConsultaSimpleFila($sql);
			$sql = "SELECT valor + 1 AS valor FROM numerador_comprobante WHERE idnumerador = 2";
            //Devuelve número comprobante
			$num_comprobante =  ejecutarConsultaSimpleFila($sql);
			/*
			$sql = "SELECT NOW() AS fechahora";
			//Devuelve fecha actual
			$fecha_hora = ejecutarConsultaSimpleFila($sql);
			*/

			
			$sql="INSERT INTO venta (
				idcliente,
				idusuario,
				tipo_comprobante,
				serie_comprobante,
				num_comprobante,
				fecha_hora,
				comentario,
				estado
				)
				VALUES (
					'$idcliente',
					'$idusuario',
					'$tipo_comprobante',
					'$serie_comprobante[valor]',
                    '$num_comprobante[valor]',
                    '$fecha_hora',
					'$observacion',
					'Aceptado')";
		
		$idventanew=ejecutarConsulta_retornarID($sql);
		
		//echo 'Variable sql -> '.$sql.'</br>';
		//echo 'Variable idventanew -> '.$idventanew.'</br>';
		
		$sql= "UPDATE numerador_comprobante SET valor='$num_comprobante[valor]' 
                   WHERE idnumerador=2";
        //echo 'Variable sql -> '.$sql.'</br>';
        ejecutarConsulta($sql) or $sw = false;
		
		$num_elementos=0;
		$sw=true;

		while ($num_elementos < count($idarticulo))
		{
			$sql_detalle = "INSERT INTO detalle_venta (
								idventa, 
								idarticulo,
								cantidad,
								idubi_origen,
								idubi_destino
							   ) 
							   VALUES (
								   '$idventanew', 
								   '$idarticulo[$num_elementos]',
								   '$cantidad[$num_elementos]',
								   '$idubi_origen[$num_elementos]',
								   '$idubi_destino[$num_elementos]'
								   )";
			ejecutarConsulta($sql_detalle) or $sw = false;
			$num_elementos=$num_elementos + 1;
		}
		//echo 'Variable sql_detalle -> '.$sql_detalle.'</br>';
		
		return $sw;
	}

	
	//Implementamos un método para anular la venta
	public function anular($idventa)
	{
		$sql="UPDATE venta 
			  SET estado='Anulado' 
			  WHERE idventa='$idventa'";

		return ejecutarConsulta($sql);
	}


	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idventa)
	{
		$sql="SELECT 
					v.idventa,
					DATE(v.fecha_hora) as fecha,
					v.idcliente,
					p.nombre as cliente,
					u.idusuario,
					u.nombre as usuario,
					v.tipo_comprobante,
					v.serie_comprobante,
					v.num_comprobante,
					v.comentario,
					v.estado 
			    FROM venta v 
				INNER JOIN persona p 
				ON v.idcliente=p.idpersona 
				INNER JOIN usuario u 
				ON v.idusuario=u.idusuario 
				WHERE v.idventa='$idventa'";

			return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetalle($idventa)
	{
		$sql="SELECT 
				dv.idventa,
				dv.idarticulo,
				a.codigo,
				a.nombre,
				a.descripcion,
				dv.cantidad,
				uo.codigo AS cod_origen,
				ud.codigo AS cod_destino
				FROM detalle_venta dv 
				INNER JOIN articulo a 
				ON dv.idarticulo=a.idarticulo 
				INNER JOIN ubicacion uo
				ON dv.idubi_origen = uo.idubicacion
				INNER JOIN ubicacion ud
				ON dv.idubi_destino = ud.idubicacion
				where dv.idventa='$idventa'";

		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT 
					v.idventa,
					DATE_FORMAT(v.fecha_hora,'%d-%m-%Y') as fecha,
					v.idcliente,
					p.nombre as cliente,
					u.idusuario,
					u.nombre as usuario,
					v.tipo_comprobante,
					v.serie_comprobante,
					v.num_comprobante,
					v.estado 
			   FROM venta v 
			   INNER JOIN persona p 
			   ON v.idcliente=p.idpersona 
			   INNER JOIN usuario u 
			   ON v.idusuario=u.idusuario 
			   ORDER by v.idventa desc";
			   
		return ejecutarConsulta($sql);		
	}

	public function ventaCabecera($idventa)
	{
		$sql= "SELECT 
				v.idventa,
				v.idcliente,
				p.nombre as operador,
				p.tipo_documento,
				p.num_documento,
				v.idusuario,
				u.nombre as usuario,
				v.tipo_comprobante,
				v.serie_comprobante,
				v.num_comprobante,
				DATE_FORMAT(v.fecha_hora,'%d-%m-%Y') as fecha,
				v.comentario
			  FROM
			  	venta v
			  INNER JOIN
			  	persona p
			  ON
			  	v.idcliente = p.idpersona
			  INNER JOIN
			  	usuario u
			  ON
			  	v.idusuario = u.idusuario
		      WHERE
			  	v.idventa = '$idventa'";

		return ejecutarConsulta($sql);
	}
	
	public function ventaDetalle($idventa)
	{
		$sql = "SELECT
					a.nombre as articulo,
					a.codigo,
					dv.cantidad,
					uo.codigo AS cod_origen
					
				FROM
					detalle_venta dv
				INNER JOIN	
					articulo a
				ON
					dv.idarticulo = a.idarticulo
				INNER JOIN ubicacion uo
				ON 
					dv.idubi_origen = uo.idubicacion
				
				WHERE
					dv.idventa = '$idventa'";

		return ejecutarConsulta($sql);
	}
}
?>
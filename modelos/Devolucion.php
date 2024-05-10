<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/conexion.php";

Class Devolucion
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idcliente,$idusuario,$tipo_comprobante,$fecha_hora,$impuesto,$total_venta,$idarticulo,$cantidad,$idubi_origen,$idubi_destino)
	{
		
			$sql = "SELECT valor FROM numerador_serie WHERE idnumerador = 3";
            //Devuelve serie comprobante
			$serie_comprobante =  ejecutarConsultaSimpleFila($sql);
			$sql = "SELECT valor + 1 AS valor FROM numerador_comprobante WHERE idnumerador = 3";
            //Devuelve número comprobante
			$num_comprobante =  ejecutarConsultaSimpleFila($sql);
			
									
			$sql="INSERT INTO devolucion (
				idcliente,
				idusuario,
				tipo_comprobante,
				serie_comprobante,
				num_comprobante,
				fecha_hora,
				estado
				)
				VALUES (
					'$idcliente',
					'$idusuario',
					'$tipo_comprobante',
					'$serie_comprobante[valor]',
                    '$num_comprobante[valor]',
                    '$fecha_hora',
					'Aceptado')";
		
		$iddevolucionnew=ejecutarConsulta_retornarID($sql);
		
		//echo 'Variable sql -> '.$sql.'</br>';
		//echo 'Variable iddevolucionnew -> '.$iddevolucionnew.'</br>';
		
		$sql= "UPDATE numerador_comprobante SET valor='$num_comprobante[valor]' 
                   WHERE idnumerador=3";
        //echo 'Variable sql -> '.$sql.'</br>';
        ejecutarConsulta($sql) or $sw = false;
		
		$num_elementos=0;
		$sw=true;

		while ($num_elementos < count($idarticulo))
		{
			$sql_detalle = "INSERT INTO detalle_devolucion (
								iddevolucion, 
								idarticulo,
								cantidad,
								idubi_origen,
								idubi_destino
							   ) 
							   VALUES (
								   '$iddevolucionnew', 
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
	public function anular($iddevolucion)
	{
		$sql="UPDATE devolucion 
			  SET estado='Anulado' 
			  WHERE iddevolucion='$iddevolucion'";
		
		//echo 'Variable sql -> '.$sql.'</br>';
		
		return ejecutarConsulta($sql);
	}


	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($iddevolucion)
	{
		$sql="SELECT 
					d.iddevolucion,
					DATE(d.fecha_hora) as fecha,
					d.idcliente,
					p.nombre as cliente,
					u.idusuario,
					u.nombre as usuario,
					d.tipo_comprobante,
					d.serie_comprobante,
					d.num_comprobante,
					d.estado 
			    FROM devolucion d 
				INNER JOIN persona p 
				ON d.idcliente=p.idpersona 
				INNER JOIN usuario u 
				ON d.idusuario=u.idusuario 
				WHERE d.iddevolucion='$iddevolucion'";

			return ejecutarConsultaSimpleFila($sql);
	}

	public function listarDetalle($iddevolucion)
	{
		$sql="SELECT 
				dd.iddevolucion,
				dd.idarticulo,
				a.codigo,
				a.nombre,
				a.descripcion,
				dd.cantidad,
				uo.codigo AS cod_origen,
				ud.codigo AS cod_destino
				FROM detalle_devolucion dd 
				INNER JOIN articulo a 
				ON dd.idarticulo=a.idarticulo 
				INNER JOIN ubicacion uo
				ON dd.idubi_origen = uo.idubicacion
				INNER JOIN ubicacion ud
				ON dd.idubi_destino = ud.idubicacion
				where dd.iddevolucion='$iddevolucion'";

		return ejecutarConsulta($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT 
					d.iddevolucion,
					DATE_FORMAT(d.fecha_hora,'%d-%m-%Y') as fecha,
					d.idcliente,
					p.nombre as cliente,
					u.idusuario,
					u.nombre as usuario,
					d.tipo_comprobante,
					d.serie_comprobante,
					d.num_comprobante,
					d.estado 
			   FROM devolucion d 
			   INNER JOIN persona p 
			   ON d.idcliente=p.idpersona 
			   INNER JOIN usuario u 
			   ON d.idusuario=u.idusuario 
			   ORDER by d.iddevolucion desc";
			   
		return ejecutarConsulta($sql);		
	}

	public function devolucionCabecera($iddevolucion)
	{
		$sql= "SELECT 
				d.iddevolucion,
				d.idcliente,
				p.nombre as operador,
				p.tipo_documento,
				p.num_documento,
				d.idusuario,
				u.nombre as usuario,
				d.tipo_comprobante,
				d.serie_comprobante,
				d.num_comprobante,
				DATE_FORMAT(d.fecha_hora,'%d-%m-%Y') as fecha
			  FROM
			  	devolucion d
			  INNER JOIN
			  	persona p
			  ON
			  	d.idcliente = p.idpersona
			  INNER JOIN
			  	usuario u
			  ON
			  	d.idusuario = u.idusuario
		      WHERE
			  	d.iddevolucion = '$iddevolucion'";

		return ejecutarConsulta($sql);
	}
	
	public function devolucionDetalle($iddevolucion)
	{
		$sql = "SELECT
					a.nombre as articulo,
					a.descripcion,
					a.codigo,
					dd.cantidad,
					ud.codigo AS cod_destino
					
				FROM
					detalle_devolucion dd
				INNER JOIN	
					articulo a
				ON
					dd.idarticulo = a.idarticulo
				INNER JOIN ubicacion ud
				ON 
					dd.idubi_destino = ud.idubicacion
				
				WHERE
					dd.iddevolucion = '$iddevolucion'";

		return ejecutarConsulta($sql);
	}
}
?>
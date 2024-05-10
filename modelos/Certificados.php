<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/conexionTradicional.php";

Class Certificado
{
	
	//Implementamos nuestro constructor
	public function __construct()
	{

	}
	public function insertar($idusuario,$idcliente,$fechaVencimiento,$partida,$idchofer,$idoperario,$numPcpi,$O2,$CO,$CO2,$H2O,$fechaCertificado,$tipoCertificado,$extension)
	{global $conexion;
		
		$sw=true;
		$sql = "SELECT NOW() AS fechahora";
			//Devuelve fecha actual
			$fecha_hora = ejecutarConsultaSimpleFila($sql);
			if($partida !== '-'){
		$sql = "SELECT valor + 1 AS valor FROM numerador_planilla WHERE idnumerador = 5";
			}else{
		$sql = "SELECT valor + 1 AS valor FROM numerador_planilla WHERE idnumerador = 6";
			}
		//Devuelve número ítem
		$num_planilla =  ejecutarConsultaSimpleFila($sql);
		try {

			$sql = "INSERT INTO certificado (
			idusuario,
			idcliente,
			num_pcpi,
			idchofer,
			idanalista,
			partida,
			O2,
			CO,
			CO2,
			H2O,
			fecha_vencimiento,
			num_comprobante,
			fecha_hora,
			tipo_certificado,
			estado,
			extension
			)
			VALUES (
				'$idusuario',
				'$idcliente',
				'$numPcpi',
				'$idchofer',
				'$idoperario',
				'$partida',
				'$O2',
				'$CO',
				'$CO2',
				'$H2O',
				'$fechaVencimiento',
				'$num_planilla[valor]',
				'$fechaCertificado',
				'$tipoCertificado',
				'Aceptado', '$extension')";

			$idcertificadonew = ejecutarConsulta_retornarID($sql) or $sw = false;
			// echo '<script>console.log("'.$conexion->error.'");</script>';
		}
		catch(Error $e){
			//echo "Hola -> ".$e->getMessage();
		
		}
		//echo 'Variable sql -> '.$sql.'</br>';
		//echo 'Variable idpddlnew -> '.$idpddlnew.'</br>';
		
		try {
			if($partida !== '-'){
		$sql= "UPDATE numerador_planilla SET valor='$num_planilla[valor]' 
				WHERE idnumerador=5";}
				else{
					$sql= "UPDATE numerador_planilla SET valor='$num_planilla[valor]' 
				WHERE idnumerador=6";
				}
		//echo 'Variable sql -> '.$sql.'</br>';
		ejecutarConsulta($sql) or $sw = false;
		
		
}
catch(Error $e){

	echo $e->getMessage();

}
		
		//echo 'Variable sql_detalle -> '.$sql_detalle.'</br>';
		
		return $sw;
		
	}
	
	
	//Para editar registros solo en tabla detalle
	public function editar($idcertificado,$idcliente,  $idchofer,$extension) {
		$sw = true;

			$sql = "UPDATE certificado SET
			idcliente = '$idcliente',
			idchofer = '$idchofer', extension = '$extension'
			WHERE idcertificado = '$idcertificado' ";

			 ejecutarConsulta($sql) or $sw = false;
		
		$num_elementos = 0;
		//$sw=true;
		




		return $sw;
	}
	
	//Implementar un método para listar los registros
	public function listar($num_pcpi){
		$sql = "SELECT 
		idcertificado,
		num_comprobante,
		IFNULL(cl.nombre,ce.idcliente) as cliente,
		ch.nombre as chofer,
		tipo_certificado as tipoCertificado,
		extension,
		u.nombre as anuladoPor,
		motivoAnulacion,
		DATE(fecha_hora) as fecha_hora,
		DATE(NOW()) as fecha_hoy,
		estado
		from certificado ce 
		LEFT JOIN choferes ch 
	ON ce.idchofer=ch.idchofer 
		LEFT JOIN clientes cl 
	ON ce.idcliente=cl.idcliente 
	LEFT JOIN usuario u
	ON ce.anuladoPor = u.idusuario 
		WHERE ce.num_pcpi IN ('$num_pcpi') 
		ORDER BY num_comprobante DESC";
		return ejecutarConsulta($sql);
	}


	public function listarEventuales(){
		$sql = "SELECT idcliente as cliente FROM certificado
		 WHERE CONCAT('', idcliente * 1) = 0";
		return ejecutarConsulta($sql);
	}
	public function anular($idcertificado,$motivo)
	{
		$sql="UPDATE certificado
			  SET estado='Anulado',
			  motivoAnulacion='$motivo',
			  anuladoPor='".$_SESSION["idusuario"]."'
			  WHERE idcertificado='$idcertificado'";

		return ejecutarConsulta($sql);
	} 

	public function certificadoCabecera($idcertificado)
	{global $conexion;
		$sql= "CALL `prCertificadoImpresion`('$idcertificado')";
		// echo '<script>console.log("'.$conexion->error.'");</script>';
		return ejecutarConsulta($sql);

	}

	public function listarDetalle($idCertificado)
	{
		$sql="SELECT 
		DATE_FORMAT(c.fecha_hora,'%h:%i') as hora,
		DATE_FORMAT(c.fecha_hora,'%Y-%m-%d') as fecha,
		c.partida,
		c.idchofer,
		c.idcliente,
		c.O2,
		c.CO,
		c.CO2,
		c.H2O,
		c.idusuario,
		c.num_comprobante,
		u.nombre as analista,
		c.tipo_certificado,
		num_pcpi
   FROM certificado c 
   LEFT JOIN usuario u
   ON c.idusuario = u.idusuario
   WHERE c.idcertificado = ".$idCertificado." 
   ORDER by c.fecha_hora";
			   
		return ejecutarConsultaSimpleFila($sql);		
	}
	public function buscarCertificado($num_comprobante)
	{
		$sql="SELECT 
		idcertificado,
		num_comprobante,
		DATE(fecha_hora) as fecha_hora,
		IFNULL(cl.nombre,ce.idcliente) as cliente,
		ch.nombre as chofer,
		tipo_certificado as tipoCertificado,
		extension,
		motivoAnulacion,
		u.nombre as anuladoPor,
		SUBSTRING(num_pcpi,10) AS num_item,
		estado
		from certificado ce 
		LEFT JOIN choferes ch 
	ON ce.idchofer=ch.idchofer 
		LEFT JOIN clientes cl 
	ON ce.idcliente=cl.idcliente
	LEFT JOIN usuario u
	ON ce.anuladoPor = u.idusuario 
		WHERE ce.num_comprobante = $num_comprobante
		ORDER BY num_comprobante DESC";
			   
		return ejecutarConsulta($sql);		
	}

	public function numeroCert($idnumerador)
	{
		$sql = "SELECT
					LPAD(valor+1,8,0) as num_planilla
				FROM
					numerador_planilla
				WHERE
				idnumerador = $idnumerador
				"
				;
				 return ejecutarConsulta($sql); 
	}
	public function numeroIdCert()
	{
		$sql = "SELECT MAX(idcertificado) as idCertificado FROM certificado "
				;
				 return ejecutarConsulta($sql); 
	}
	public function numeroIdCertDescarga($num_pcpi)
	{
		$sql = "SELECT MIN(idcertificado) AS idCertificado FROM certificado WHERE num_pcpi = '$num_pcpi'"
				;
				 return ejecutarConsulta($sql); 
	}
	public function numeroPddlMax()
	{
		$sql = "SELECT
		LPAD(MAX(num_planilla)+1,8,0) as num_planilla
	FROM
		pddl p
";
		//echo 'Variable sql -> '.$sql.'</br>';
		return ejecutarConsulta($sql);
	}

	public function consultaCertificados($tipo_certificado, $num_comprobante)
	{
		$sql="SELECT 
		idcertificado,
		num_comprobante,
		dp.lote,
		dp.partida_origen,
		DATE(ce.fecha_hora) as fecha_hora,
		SUBSTRING_INDEX(SUBSTRING(IFNULL(cl.nombre,ce.idcliente),
		LOCATE(')',IFNULL(cl.nombre,ce.idcliente))+2)
		,'(',1) AS cliente,
		ch.nombre as chofer,
		tipo_certificado as tipoCertificado,
		extension,
		motivoAnulacion,
		u.nombre as anuladoPor,
		uA.nombre as analista,
		SUBSTRING(num_pcpi,10) AS num_item,
		ce.estado
		from certificado ce 
		LEFT JOIN pcpi p
		ON SUBSTRING(ce.num_pcpi,1,10) = p.num_planilla
		LEFT JOIN detalle_pcpi dp
		ON p.idpcpi = dp.idpcpi
		AND dp.num_item = SUBSTRING(ce.num_pcpi,10)
		LEFT JOIN choferes ch 
	ON ce.idchofer=ch.idchofer 
		LEFT JOIN clientes cl 
	ON ce.idcliente=cl.idcliente
	LEFT JOIN usuario u
	ON ce.anuladoPor = u.idusuario 
	LEFT JOIN usuario uA
	ON ce.idanalista = uA.idusuario 
	WHERE ce.tipo_certificado REGEXP '^$tipo_certificado$'		
	AND ce.num_comprobante REGEXP '$num_comprobante$'		
		ORDER BY num_comprobante DESC";
			   
		return ejecutarConsulta($sql);		
	}
}
?>
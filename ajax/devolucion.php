<?php 
if (strlen(session_id()) < 1) 
  session_start();

require_once "../modelos/Devolucion.php";

$devolucion=new Devolucion();

$iddevolucion=isset($_POST["iddevolucion"])? limpiarCadena($_POST["iddevolucion"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";

$idubi_origen= isset($_POST['idubi_origen']) ? $_POST['idubi_origen'] :"";
$idubi_destino= isset($_POST['ubi_des']) ? $_POST['ubi_des'] :"";
  
$idusuario=$_SESSION["idusuario"];
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$serie_comprobante=isset($_POST["serie_comprobante"])? limpiarCadena($_POST["serie_comprobante"]):"";
$num_comprobante=isset($_POST["num_comprobante"])? limpiarCadena($_POST["num_comprobante"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$idarticulo = isset($_POST['idarticulo']) ? $_POST['idarticulo'] : "";
$cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : "";
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($iddevolucion)){
			$impuesto=0;
			$total_venta=0;

			$rspta=$devolucion->insertar($idcliente,$idusuario,$tipo_comprobante,$fecha_hora,$impuesto,$total_venta,$idarticulo,$cantidad,$idubi_origen,$idubi_destino);
			echo $rspta ? "Devolucion registrada" : "No se pudieron registrar todos los datos de la devolucion";
		}
		else {
		}
	break;

	case 'anular':
		$rspta=$devolucion->anular($iddevolucion);
 		echo $rspta ? "Devolucion anulada" : "Devolucion no se puede anular";
	break;

	case 'mostrar':
		$rspta=$devolucion->mostrar($iddevolucion);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listarDetalle':
		//Recibimos el idingreso
		$id=$_GET['id'];

		$rspta = $devolucion->listarDetalle($id);
		$total=0;
		echo '<thead style="background-color:#A9D0F5">
                                    <th>Opciones</th>
                                    <th>Codigo</th>
                                    <th>Artículo</th>
									<th>Descripcion</th>
                                    <th>Cantidad</th>
                                    <th>Origen</th>
                                    <th>Destino</th>
                                </thead>';

		while ($reg = $rspta->fetch_object())
				{
					echo '<tr class="filas"><td></td><td>'.$reg->codigo.'</td><td>'.$reg->nombre.'</td><td>'.$reg->descripcion.'</td><td>'.$reg->cantidad.'</td><td>'.$reg->cod_origen.'</td><td>'.$reg->cod_destino.'</td><td>'.$reg->subtotal.'</td></tr>';
					$total=$total+($reg->precio_venta*$reg->cantidad-$reg->descuento);
				}
		/*
		echo '<tfoot>
                                    <th>TOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><h4 id="total">S/.'.$total.'</h4><input type="hidden" name="total_venta" id="total_venta"></th> 
                                </tfoot>';
		*/
		;
	break;

	case 'listar':
		$rspta=$devolucion->listar();
 		//Vamos a declarar un array
 		$data= Array();

		 while ($reg=$rspta->fetch_object())
		 {
			if($reg->tipo_comprobante=='Ticket')
			{
				$url = '../reportes/exTicket.php?id='; //Ruta del archivo exTicket
			}
			else
			{
				$url = '../reportes/exDevolucion.php?id='; //Ruta del archivo exFactura
			}

 			$data[]=array(
 				"0"=>(
					 ($reg->estado=='Aceptado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->iddevolucion.')"><i class="fa fa-eye"></i></button>'.
						' <button class="btn btn-danger" onclick="anular('.$reg->iddevolucion.')"><i class="fa fa-close"></i></button>':
						'<button class="btn btn-warning" onclick="mostrar('.$reg->iddevolucion.')"><i class="fa fa-eye"></i></button>'
					 ).
					 '<a target="_blank" href="'.$url.$reg->iddevolucion.'">
						  <button class="btn btn-info">
						 <i class="fa fa-file"></i>
						 </button>
					 </a>'
					 ,
 				"1"=>$reg->fecha,
 				"2"=>$reg->cliente,
 				"3"=>$reg->usuario,
 				"4"=>$reg->tipo_comprobante,
 				"5"=>$reg->serie_comprobante.'-'.$reg->num_comprobante,
 				"6"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
 				'<span class="label bg-red">Anulado</span>'
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;

	case 'selectMovDestino':
            require_once "../modelos/Movimiento.php";
            $movimiento = new Movimiento();

            $rspta = $movimiento->selectUno();

            while($reg = $rspta->fetch_object())
            {
                echo '<option value='.$reg->idubicacion.'>'
                        .$reg->codigo.' - '.$reg->descripcion.
                      '</option>';
            }
        break;

	case 'selectCliente':
		require_once "../modelos/Persona.php";
		$persona = new Persona();

		$rspta = $persona->listarc();

		while ($reg = $rspta->fetch_object())
				{
				echo '<option value=' . $reg->idpersona . '>' . $reg->nombre . '</option>';
				}
	break;
	
	case 'listarArticulos':
		//Recibimos el idcliente
		$idcliente=$_GET['idcliente'];
		require_once '../modelos/Articulo.php';
        $articulo = new Articulo();

        $rspta = $articulo->listarActivosExt($idcliente);
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
               "0"=> '<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.','.$reg->idubicacion.',\''.htmlspecialchars($reg->nombre).'\',\''.htmlspecialchars($reg->descripcion).'\',\''.htmlspecialchars($reg->categoria).'\',\''.$reg->codigo.'\',\''.$reg->c_ubi.'\','.$reg->cantidad.')">
                                <span class="fa fa-plus"></span>
                            </button>',
					
                "1"=>$reg->nombre,
				"2"=>$reg->descripcion,
                "3"=>$reg->categoria,
                "4"=>$reg->codigo,
				"5"=>$reg->c_ubi,
                "6"=>$reg->cantidad,
                "7"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px'>"
             );
        }
        $results = array(
            "sEcho"=>1, //Informacion para el datable
            "iTotalRecords" =>count($data), //enviamos el total de registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
            "aaData" =>$data
        );
        echo json_encode($results);

    break;
	
		
	case 'listarArticulosUno':

            require_once '../modelos/Articulo.php';
            $articulo = new Articulo();
			
			$idartubi=$_GET['idartubi'];
			$idcliente=$_GET['idcliente'];
			$rspta = $articulo->listarActivosUbiDos($idartubi,$idcliente);
            $data = Array();

            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0"=> '<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.','.$reg->idubicacion.',\''.htmlspecialchars($reg->nombre).'\',\''.htmlspecialchars($reg->descripcion).'\',\''.htmlspecialchars($reg->categoria).'\',\''.$reg->codigo.'\',\''.$reg->c_ubi.'\','.$reg->cantidad.')">
                                <span class="fa fa-plus"></span>
                            </button>',
					
                    "1"=>$reg->nombre,
					"2"=>$reg->descripcion,
                    "3"=>$reg->categoria,
                    "4"=>$reg->codigo,
					"5"=>$reg->c_ubi,
                    "6"=>$reg->cantidad,
                    "7"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px'>"
                );
            }
            $results = array(
                "sEcho"=>1, //Informacion para el datable
                "iTotalRecords" =>count($data), //enviamos el total de registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
                "aaData" =>$data
            );
            echo json_encode($results);

        break;
	
}
?>
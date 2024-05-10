<?php
    
    require_once '../modelos/Movimiento.php';
    
    if(strlen(session_id()) < 1){
        session_start();
    }
	
    $movimiento = new Movimiento();

	$idubicacion= isset($_POST['idubicacion']) ? $_POST['idubicacion'] :"";
    
	$iddestino=isset($_POST["ubi_des"])? $_POST["ubi_des"]:"";
    $idusuario= $_SESSION['idusuario'];
    $fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
    $idarticulo = isset($_POST['idarticulo']) ? $_POST['idarticulo'] : "";
    $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : "";
    
	$idmovimiento=isset($_POST["idmovimiento"])? $_POST["idmovimiento"]:"";
	
    switch($_GET["op"])
    {
        case 'guardaryeditar':
            if (empty($idmovimiento)){
				$tipo_comprobante = 8;

                $rspta=$movimiento->insertar($idusuario,$tipo_comprobante,$idubicacion,$iddestino,$idarticulo,$cantidad);
                echo $rspta ? "Movimiento registrado" : "Movimiento no se pudo registrar";
            }
        break;
		
		case 'validartipocomprobante':
			//Validamos si el tipo de comprobante puede ser anulado desde el menú Movimiento
			$rspta = $movimiento->validartipocomprobante($idmovimiento);
			echo json_encode($rspta);
		break;
		
		case 'validar':
			//Validamos si hay existencia de articulo en ubicacion
			$rspta = $movimiento->validar($idmovimiento);
			echo json_encode($rspta);
		break;
		
        case 'anular':
				//Recibimos el idingreso
				$id=$_GET['id'];
                $rspta = $movimiento->anular($id);
                echo $rspta ? "Movimiento anulado" : "Movimiento no se pudo anular";
        break;

        case 'mostrar':
            $rspta = $movimiento->mostrar($idmovimiento);
            echo json_encode($rspta);
        break;

        case 'listarDetalle':
            //Recibimos el idingreso
            $id=$_GET['id'];

            $rspta = $movimiento->listarDetalle($id);
            
            $total = 0;
            
            echo '<thead style="background-color:#A9D0F5">
                    <th>Opciones</th>
                    <th>Codigo</th>
                    <th>Articulo</th>
					<th>Descripcion</th>
                    <th>Cantidad</th>
                    <th>Ubicacion Origen</th>
                    <th>Ubicacion Destino</th>
                </thead>';

            while($reg = $rspta->fetch_object())
            {
                echo '<tbody>
                        <tr class="filas">
                            <td></td> 
                            <td>'.$reg->codigo.'</td>
							<td>'.$reg->nombre.'</td> 
							<td>'.$reg->descripcion.'</td>
                            <td>'.$reg->cantidad.'</td> 
                            <td>'.$reg->cod_origen.'</td> 
                            <td>'.$reg->cod_destino.'</td> 
                        </tr>
                      </tbody>';

                $total += ($reg->precio_compra*$reg->cantidad);
            }
			/*
            echo '<tfoot>
                    <th>TOTAL</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>
                    <h4 id="total">$ '.$total.'</h4>
                    <input type="hidden" name="total_compra" id="total_compra">
                    </th>
                </tfoot>';
			*/
			;
        break;

        case 'listar':
            $rspta = $movimiento->listar();
            $data = Array();
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0"=> ($reg->estado == 'Aceptado') ? 
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idmovimiento.')"><li class="fa fa-eye"></li></button>'.
                        ' <button class="btn btn-danger" onclick="anular('.$reg->idmovimiento.')"><li class="fa fa-close"></li></button>'
                        :
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idmovimiento.')"><li class="fa fa-eye"></li></button>',
                    "1"=>$reg->fecha,
                    "2"=>$reg->tipo_comprobante,
					"3"=>$reg->usuario,
                    "4"=>'Movimiento',
					"5"=>$reg->serie_comprobante.'-'.$reg->num_comprobante,
					"6"=>($reg->estado=='Aceptado') ?
                         '<span class="label bg-green">Aceptado</span>'
                         :      
                         '<span class="label bg-red">Anulado</span>'
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
		
		/*
		case 'selectMovOrigen':
            require_once "../modelos/Movimiento.php";
            $movimiento = new Movimiento();

            $rspta = $movimiento->select();

            while($reg = $rspta->fetch_object())
            {
                echo '<option value='.$reg->idubicacion.'>'
                        .$reg->codigo.' - '.$reg->descripcion.
                      '</option>';
            }
        break;
		*/
		
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
		
		case 'selectMovDestinoDos':
            require_once "../modelos/Movimiento.php";
            $movimiento = new Movimiento();

            $rspta = $movimiento->selectDos();

            while($reg = $rspta->fetch_object())
            {
                echo '<option value='.$reg->idubicacion.'>'
                        .$reg->codigo.' - '.$reg->descripcion.
                      '</option>';
            }
        break;
		
        case 'selectProveedor':
            
            require_once '../modelos/Persona.php';
            $persona = new Persona();

            $rspta = $persona->listarp();

            while($reg = $rspta->fetch_object())
            {
                echo '<option value='.$reg->idpersona.'>'.$reg->nombre.'</option>';
            }
        break;

        case 'listarArticulos':

            require_once '../modelos/Articulo.php';
            $articulo = new Articulo();

            $rspta = $articulo->listarActivosUbi();
            $data = Array();

            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0"=> '<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.','.$reg->idubicacion.',\''.$reg->codigo.'\',\''.htmlspecialchars($reg->nombre).'\',\''.htmlspecialchars($reg->descripcion).'\',\''.htmlspecialchars($reg->categoria).'\',\''.$reg->c_ubi.'\','.$reg->cantidad.')">
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
			$rspta = $articulo->listarActivosUbiUno($idartubi);
            $data = Array();

            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0"=> '<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.','.$reg->idubicacion.',\''.$reg->codigo.'\',\''.htmlspecialchars($reg->nombre).'\',\''.htmlspecialchars($reg->descripcion).'\',\''.htmlspecialchars($reg->categoria).'\',\''.$reg->c_ubi.'\','.$reg->cantidad.')">
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
		

		case 'buscarProximo':
            $rspta = $movimiento->buscarProximo($idmovimiento);
            echo json_encode($rspta);
        break;
		
		
		
		
		
		
		
		
		
		
		
    }

?>
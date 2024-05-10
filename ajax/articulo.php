<?php
    
    require_once '../modelos/Articulo.php';
	
	if(strlen(session_id()) < 1){
        session_start();
    }

	$articulo = new Articulo();

    $idarticulo=isset($_POST["idarticulo"])? limpiarCadena($_POST["idarticulo"]):"";
    $idcategoria=isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
	$idunidad=isset($_POST["idunidad"])? limpiarCadena($_POST["idunidad"]):"";
	$idusuario= $_SESSION['idusuario'];
	$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
    $nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
    $cantidad=isset($_POST["cantidad"])? limpiarCadena($_POST["cantidad"]):"";
    $descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
    $imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";
	
		
    switch($_GET["op"])
    {
        case 'guardaryeditar':

            if(!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
            {
                $imagen = $_POST["imagenactual"];
            }
            else
            {
                $ext = explode(".",$_FILES["imagen"]["name"]);
                if($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
                {
                    $imagen = round(microtime(true)).'.'.end($ext);
                    move_uploaded_file($_FILES['imagen']['tmp_name'], "../files/articulos/" . $imagen);
                }
            }


            if (empty($idarticulo)){
                $rspta=$articulo->insertar($idcategoria,$idunidad,$idusuario,$codigo,$nombre,$cantidad,$descripcion,$imagen);
                echo $rspta ? "Articulo registrado" : "Articulo no se pudo registrar";
            }
            else {
                $rspta=$articulo->editar($idarticulo,$idcategoria,$idunidad,$idusuario,$codigo,$nombre,$descripcion,$imagen);
                echo $rspta ? "Articulo actualizado" : "Articulo no se pudo actualizar";
            }
        break;

        case 'desactivar':
                $rspta = $articulo->desactivar($idarticulo);
                echo $rspta ? "Articulo desactivada" : "Articulo no se pudo desactivar";
        break;

        case 'activar':
            $rspta = $articulo->activar($idarticulo);
            echo $rspta ? "Articulo activado" : "Articulo no se pudo activar";
        break;

        case 'mostrar':
            $rspta = $articulo->mostrar($idarticulo);
            echo json_encode($rspta);
        break;

        case 'listar':
            $rspta = $articulo->listar();
            $data = Array();
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0"=> ($reg->condicion) ? 
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idarticulo.')"><li class="fa fa-pencil"></li></button>'.
                        ' <button class="btn btn-danger" onclick="desactivar('.$reg->idarticulo.')"><li class="fa fa-close"></li></button>'
                        :
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idarticulo.')"><li class="fa fa-pencil"></li></button>'.
                        ' <button class="btn btn-primary" onclick="activar('.$reg->idarticulo.')"><li class="fa fa-check"></li></button>'
                        ,
                    "1"=>$reg->nombre,
					"2"=>$reg->descripcion,
                    "3"=>$reg->categoria,
                    "4"=>$reg->codigo,
                    "5"=>$reg->unidad,
					"6"=>$reg->stock,
                    "7"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px'>",
                    "8"=>($reg->condicion) ?
                         '<span class="label bg-green">Activado</span>'
                         :      
                         '<span class="label bg-red">Desactivado</span>'
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
	
        case 'selectCategoria':
            require_once "../modelos/Categoria.php";
            $categoria = new Categoria();

            $rspta = $categoria->select();

            while($reg = $rspta->fetch_object())
            {
                echo '<option value='.$reg->idcategoria.'>'
                        .$reg->nombre.
                      '</option>';
            }
        break;
		
		case 'selectUnidad':
            require_once "../modelos/Unidad.php";
            $unidad = new Unidad();

            $rspta = $unidad->select();

            while($reg = $rspta->fetch_object())
            {
                echo '<option value='.$reg->idunidad.'>'
                        .$reg->nombre.
                      '</option>';
            }
        break;

		case 'selectArticulo':
            $rspta = $articulo->listarSimple();

            while($reg = $rspta->fetch_object())
            {
                echo '<option value='.$reg->idarticulo.'>'
                        .$reg->codigo.' - '.$reg->nombre.' - '.$reg->descripcion.
                      '</option>';
            }
        break;

    }

?>
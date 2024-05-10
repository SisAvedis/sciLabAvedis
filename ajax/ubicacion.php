<?php
    
    require_once '../modelos/Ubicacion.php';

    $ubicacion = new ubicacion();

    $idubicacion=isset($_POST["idubicacion"])? limpiarCadena($_POST["idubicacion"]):"";
	$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
	$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

    switch($_GET["op"])
    {
        case 'guardaryeditar':
            if (empty($idubicacion)){
                $rspta=$ubicacion->insertar($codigo,$descripcion);
                echo $rspta ? "Ubicacion registrada" : "Ubicacion no se pudo registrar";
            }
            else {
                $rspta=$ubicacion->editar($idubicacion,$codigo,$descripcion);
                echo $rspta ? "Ubicacion actualizada" : "Ubicacion no se pudo actualizar";
            }
        break;

        case 'desactivar':
                $rspta = $ubicacion->desactivar($idubicacion);
                echo $rspta ? "Ubicacion desactivada" : "Ubicacion no se pudo desactivar";
        break;

        case 'activar':
            $rspta = $ubicacion->activar($idubicacion);
            echo $rspta ? "Ubicacion activada" : "Ubicacion no se pudos activar";
        break;

        case 'mostrar':
            $rspta = $ubicacion->mostrar($idubicacion);
            echo json_encode($rspta);
        break;

        case 'listar':
            $rspta = $ubicacion->listar();
            $data = Array();
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0"=> ($reg->condicion) ? 
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idubicacion.')"><li class="fa fa-pencil"></li></button>'.
                        ' <button class="btn btn-danger" onclick="desactivar('.$reg->idubicacion.')"><li class="fa fa-close"></li></button>'
                        :
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idubicacion.')"><li class="fa fa-pencil"></li></button>'.
                        ' <button class="btn btn-primary" onclick="activar('.$reg->idubicacion.')"><li class="fa fa-check"></li></button>'
                        ,
                    "1"=>$reg->codigo,
                    "2"=>$reg->descripcion,
                    "3"=>($reg->condicion) ?
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
    }

?>
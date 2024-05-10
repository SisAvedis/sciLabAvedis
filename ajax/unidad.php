<?php
    
    require_once '../modelos/Unidad.php';

    $unidad = new Unidad();

    $idunidad=isset($_POST["idunidad"])? limpiarCadena($_POST["idunidad"]):"";
	$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
	$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

    switch($_GET["op"])
    {
        case 'guardaryeditar':
            if (empty($idunidad)){
                $rspta=$unidad->insertar($nombre,$descripcion);
                echo $rspta ? "Unidad de Medida registrada" : "Unidad de Medida no se pudo registrar";
            }
            else {
                $rspta=$unidad->editar($idunidad,$nombre,$descripcion);
                echo $rspta ? "Unidad de Medida actualizada" : "Unidad de Medida no se pudo actualizar";
            }
        break;

        case 'desactivar':
                $rspta = $unidad->desactivar($idunidad);
                echo $rspta ? "Unidad de Medida desactivada" : "Unidad de Medida no se pudo desactivar";
        break;

        case 'activar':
            $rspta = $unidad->activar($idunidad);
            echo $rspta ? "Unidad de Medida activada" : "Unidad de Medida no se pudos activar";
        break;

        case 'mostrar':
            $rspta = $unidad->mostrar($idunidad);
            echo json_encode($rspta);
        break;

        case 'listar':
            $rspta = $unidad->listar();
            $data = Array();
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0"=> ($reg->condicion) ? 
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idunidad.')"><li class="fa fa-pencil"></li></button>'.
                        ' <button class="btn btn-danger" onclick="desactivar('.$reg->idunidad.')"><li class="fa fa-close"></li></button>'
                        :
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idunidad.')"><li class="fa fa-pencil"></li></button>'.
                        ' <button class="btn btn-primary" onclick="activar('.$reg->idunidad.')"><li class="fa fa-check"></li></button>'
                        ,
                    "1"=>$reg->nombre,
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
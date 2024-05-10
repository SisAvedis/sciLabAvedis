<?php
    
    require_once '../modelos/Sector.php';

    $sector = new Sector();

    $idsector=isset($_POST["idsector"])? limpiarCadena($_POST["idsector"]):"";
	$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
	$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

    switch($_GET["op"])
    {
        case 'guardaryeditar':
            if (empty($idsector)){
                $rspta=$sector->insertar($nombre,$descripcion);
                echo $rspta ? "Sector registrado" : "Sector no se pudo registrar";
            }
            else {
                $rspta=$sector->editar($idsector,$nombre,$descripcion);
                echo $rspta ? "Sector actualizado" : "Sector no se pudo actualizar";
            }
        break;

        case 'desactivar':
                $rspta = $sector->desactivar($idsector);
                echo $rspta ? "Sector desactivado" : "Sector no se pudos desactivar";
        break;

        case 'activar':
            $rspta = $sector->activar($idsector);
            echo $rspta ? "Sector activado" : "Sector no se pudos activar";
        break;

        case 'mostrar':
            $rspta = $sector->mostrar($idsector);
            echo json_encode($rspta);
        break;

        case 'listar':
            $rspta = $sector->listar();
            $data = Array();
            while ($reg = $rspta->fetch_object()) {
                $data[] = array(
                    "0"=> ($reg->condicion) ? 
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idsector.')"><li class="fa fa-pencil"></li></button>'.
                        ' <button class="btn btn-danger" onclick="desactivar('.$reg->idsector.')"><li class="fa fa-close"></li></button>'
                        :
                        '<button class="btn btn-warning" onclick="mostrar('.$reg->idsector.')"><li class="fa fa-pencil"></li></button>'.
                        ' <button class="btn btn-primary" onclick="activar('.$reg->idsector.')"><li class="fa fa-check"></li></button>'
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
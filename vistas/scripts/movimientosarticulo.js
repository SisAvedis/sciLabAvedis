var tabla;

//Funcion que se ejecuta al inicio
function init()
{
    //listar();

    //Cargamos los items al select artículo
    $.post("../ajax/articulo.php?op=selectArticulo", function (r) {
        $("#idarticulo").html(r);
        $('#idarticulo').selectpicker('refresh');
    });	

}

function listar()
{
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var idarticulo = $("#idarticulo").val();
	
	console.log('Valor de idarticulo -> '+idarticulo);

    tabla = $('#tblistado')
        .dataTable(
            {
                "aProcessing":true, //Activamos el procesamiento del datatables
                "aServerSide":true, //Paginacion y filtrado realizados por el servidor
                dom: "Bfrtip", //Definimos los elementos del control de tabla
                buttons:[
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdf'
                ],
                "ajax":{
                    url: '../ajax/consultas.php?op=pr_movimientosarticulo',
                    data:{fecha_inicio:fecha_inicio, fecha_fin:fecha_fin,idarticulo:idarticulo},
					//data:{idarticulo:idarticulo},
                    type: "get",
                    dataType:"json",
                    error: function(e) {
                        console.log(e.responseText);
                    }
                },
                "bDestroy": true,
                "iDisplayLength": 5, //Paginacion
                "order": [[0,"desc"]] //Ordenar (Columna, orden)
            
            })
        .DataTable();
}


init();
var tabla;

//Funcion que se ejecuta al inicio
function init()
{
    mostrarform(false,false);
    listar();

    $("#formulario").on("submit",function(e)
    {
        guardaryeditar(e);
    });
	/*
    $.post(
        "../ajax/movimiento.php?op=selectMovOrigen",
        function(data)
        {
            $("#idorigen").html(data);
            $("#idorigen").selectpicker('refresh');
        }
    );
	*/
	$.post(
        "../ajax/movimiento.php?op=selectMovDestino",
        function(data)
        {
            $("#iddestino").html(data);
            $("#iddestino").selectpicker('refresh');
        }
    );
}

//funcion limpiar
function limpiar()
{
    $("#idproveedor").val("");
    $("#proveedor").val("");
    $("#serie_comprobante").val("");
    $("#num_comprobante").val("");
    $("#fecha_hora").val("");
    $("#impuesto").val("0");

    $("#total_compra").val("");
    $(".filas").remove();
    $("#total").html(0);

    //obtenemos la fecha actual
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day);
    $("#fecha_hora").val(today);

    //Marcar el primer tipo de documento
    $("#tipo_comprobante").val("Boleta");
    $("#tipo_comprobante").selectpicker('refresh');

}

//funcion mostrar formulario
function mostrarform(flag1,flag2)
{
    limpiar();

    if(flag1 && flag2)
    {
        $("#listadoregistros").hide();
		$("#listadocabecera").hide();
        $("#formularioregistros").show();
        //$("#btnGuardar").prop("disabled",false);
        $("#btnagregar").hide();
		consultarDetalle();
        listarArticulos();
		
        $("#btnguardar").show();
        $("#btnCancelar").show();
        detalles = 0;
        $("#btnAgregarArt").show();
    }
	else if(!flag1 && flag2)
    {
        $("#listadoregistros").hide();
		$("#listadocabecera").show();
        $("#formularioregistros").show();
        $("#btnagregar").hide();
		
    }
    else if(!flag1 && !flag2)
    {
        $("#listadoregistros").show();
		$("#listadocabecera").hide();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
}

//Funcion cancelarform
function cancelarform()
{
    limpiar();
    mostrarform(false,false);
}

//Funcion listar
function listar()
{
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
                    url: '../ajax/movimiento.php?op=listar',
                    type: "get",
                    dataType:"json",
                    error: function(e) {
                        console.log(e.responseText);
                    }
                },
                "bDestroy": true,
                "iDisplayLength": 5, //Paginacion
                "order": [[5,"desc"]] //Ordenar (Columna, orden)
            
            })
        .DataTable();
}


function listarArticulos()
{
    tabla = $('#tblarticulos')
        .dataTable(
            {
                "aProcessing":true, //Activamos el procesamiento del datatables
                "aServerSide":true, //Paginacion y filtrado realizados por el servidor
                dom: "Bfrtip", //Definimos los elementos del control de tabla
                buttons:[
                    
                ],
                "ajax":{
                    url: '../ajax/movimiento.php?op=listarArticulos',
                    type: "get",
					data: {},
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


function listarArticulosUno(idartubi)
{
    //var arrIdArticulo = JSON.stringify(idarticulo);
	tabla = $('#tblarticulos')
        .dataTable(
            {
                "aProcessing":true, //Activamos el procesamiento del datatables
                "aServerSide":true, //Paginacion y filtrado realizados por el servidor
                dom: "Bfrtip", //Definimos los elementos del control de tabla
                buttons:[
                    
                ],
                "ajax":{
                    url: '../ajax/movimiento.php?op=listarArticulosUno',
                    data: {idartubi:idartubi},
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

//funcion para guardar o editar
function guardaryeditar(e)
{
    e.preventDefault(); //No se activará la acción predeterminada del evento
	//$("#btnGuardar").prop("disabled",true);
    var formData = new FormData($("#formulario")[0]);
    
	for (var pair of formData.entries()){
		
		console.log(pair[0]+', '+pair[1]);
	}


    $.ajax({
        url: "../ajax/movimiento.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos)
        {
            console.log("success -> "+datos);
            bootbox.alert(datos);
            mostrarform(false);
            listar();

        },
        error: function(error)
        {
            console.log("error: " + error);
        } 
    });

    limpiar();
}

function mostrar(idmovimiento)
{
    $.post(
        "../ajax/movimiento.php?op=mostrar",
        {idmovimiento:idmovimiento},
        function(data,status)
        {

            data = JSON.parse(data);
            mostrarform(false,true);

            $("#nombre_persona").val(data.nombre_persona);
            $("#tipo_comprobante_cab").val(data.tipo_comprobante);
            $("#serie_comprobante_cab").val(data.serie_comprobante);
            $("#num_comprobante_cab").val(data.num_comprobante);
			$("#fecha_hora_cab").val(data.fecha);
            //Ocultar y mostrar botones
            $("#btnGuardar").hide();
            $("#btnCancelar").show();
            $("#btnAgregarArt").hide();
			
            $.post(
                "../ajax/movimiento.php?op=listarDetalle&id="+idmovimiento,
                function(r)
                {
                    $("#detalles").html("");
                    $("#detalles").html(r);
                }
            );
			

        }
    );
}

function buscarProximoNumero()
{
    $.post(
        "../ajax/movimiento.php?op=buscarProximo",
        {idmovimiento:idmovimiento},
        function(data,status)
        {

            data = JSON.parse(data);
            mostrarform(false,true);

            $("#nombre_persona").val(data.nombre_persona);
            $("#tipo_comprobante_cab").val(data.tipo_comprobante);
            $("#serie_comprobante_cab").val(data.serie_comprobante);
            $("#num_comprobante_cab").val(data.num_comprobante);
            

			//var fechaOriginal = JSON.stringify(data.fecha_hora);
			//fechaOriginal = fechaOriginal.substring(1,11);
			//$("#fecha_hora_cab").val(fechaOriginal);
			$("#fecha_hora_cab").val(data.fecha);
            //Ocultar y mostrar botones
            $("#btnGuardar").hide();
            //$("#btnCancelar").show();
            $("#btnAgregarArt").hide();
			/*
            $.post(
                "../ajax/ingreso.php?op=listarDetalle&id="+idingreso,
                function(r)
                {
                    $("#detalles").html("");
                    $("#detalles").html(r);
                }
            );
			*/

        }
    );
}


function anular(idmovimiento)
{
    $.post(
        "../ajax/movimiento.php?op=validartipocomprobante",
        {idmovimiento:idmovimiento},
        function(data,status)
		{

			data = JSON.parse(data);
			var tipoc = data.tipoc;
			if(tipoc == 8)
			{
				bootbox.confirm("¿Estas seguro de anular el Movimiento?",function(result){
					if(result)
					{
						$.post(
						"../ajax/movimiento.php?op=validar",
						{idmovimiento:idmovimiento},
						function(data,status)
						{		
							data = JSON.parse(data);
							var res = data.resul;
							
							if(res == 1)
							{
								$.post(
								"../ajax/movimiento.php?op=anular&id="+idmovimiento,
								function(e)
								{
									bootbox.alert(e);
									tabla.ajax.reload();
								}
								);
							}else
							{
								var msg = 'El movimiento no se pudo anular. Verifique existencias';
								bootbox.alert(msg);
							}
						}	
						);
					}	
				});
			}else
			{
				var msg = 'El movimiento debe anularse desde la transacción que lo generó';
				bootbox.alert(msg);
			}
		}	
    );
	
}

//Variables
var impuesto = 16;
var cont = 0;
var detalles= 0;

$("#btnGuardar").hide();
$("#tipo_comprobante").change(marcarImpuesto);

function marcarImpuesto()
{
    var tipo_comprobante = $("#tipo_comprobante option:selected").text();
    if(tipo_comprobante == 'Factura')
    {
        $("#impuesto").val(impuesto);
    }
    else
    {
        $("#impuesto").val('0');
    }
}

function agregarDetalle(idarticulo,idubicacion,codigo,articulo,descripcion,categoria,c_ubi,cantidad)
{
    //var cantidad = 1;
    var precio_compra = 1;
    var precio_venta = 3;
	
	$.post(
        "../ajax/movimiento.php?op=selectMovDestinoDos",
        function(data)
        {
            for (var i=0;i<cont;i++){
			$("#ubi_des"+i).html(data);
            $("#ubi_des"+i).selectpicker('refresh');
        }
		}
    );

    if(idarticulo != "")
    {
        //var subtotal = cantidad * precio_compra;
        var fila = '<tr class="filas" id="fila'+cont+'"> ' +
                      '<td>'+
                           '<button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button>'+
                       '</td>'+
					  '<td>' +
                          '<input type="hidden" name="codigo[]" value="'+codigo+'">'+
                           codigo +
                       '</td>'+
                      '<td>' +
                          '<input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+
                           articulo +
                       '</td>'+
					  '<td>' +
                          '<input type="hidden" name="descripcion[]" value="'+descripcion+'">'+
						  descripcion +
                       '</td>'+ 
					  '<td>' +
                          '<input type="hidden" name="descripcion[]" value="'+categoria+'">'+
						  categoria +
                       '</td>'+ 
                      '<td>' +
                          '<input type="number" step="0.01" name="cantidad[]" min="0" max="'+cantidad+'" id="cantidad[]" value="'+cantidad+'">'+
                       '</td>'+
                      '<td>' +
                          '<input type="hidden" name="idubicacion[]" value="'+idubicacion+'">'+ 
							c_ubi+
                       '</td>'+
                      '<td>' +
						  '<select name="ubi_des[]" id="ubi_des'+cont+'" data-live-search="true" class="form-control selectpicker"></select>'+
                          //'<select name="ubi_des'+cont+'" id="ubi_des'+cont+'" data-live-search="true" class="form-control //selectpicker"></select>'+
 					   '</td>'+
                      //'<td>' +
                          //'<button type="button" class="btn btn-info" onclick="verificaStock()">'+
                            //'<i class="fa fa-refresh"></i>'+
                          //'</button>'+
                       //'</td>'+
                   '</tr>';

        cont++;
        detalles++;
        $("#detalles").append(fila);
		//modificarSubtotales(); 
    }
    else
    {
        alert("Error al ingresar el detalle, revisar los datos del articulo");
    }
	evaluar();
	consultarDetalle();

}
function consultarDetalle()
{	
	var idart = document.getElementsByName("idarticulo[]");
	var idubi = document.getElementsByName("idubicacion[]");
	var canti = document.getElementsByName("cantidad[]");
	var idubd = document.getElementsByName("ubi_des[]");
	var tamañoCant = idart.length;
	//console.log('Valor de tamañoCant -> '+tamañoCant);
	var idartubi = '';
	var idubides = '';
	for (var i=0;i<tamañoCant;i++){
	idartubi += idubi[i].value+','+idart[i].value+','+canti[i].value+',';
	console.log('Valor de idartubi -> '+idartubi);
	idubides += idubd[i].value+'|';
	console.log('Valor de idubides -> '+idubides);
	}
	
	if (tamañoCant !== 0){  
		listarArticulosUno(idartubi);
	}else{
		listarArticulos();
	}

}

function modificarSubtotales()
{
    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("c_ubi[]");
    var sub = document.getElementsByName("subtotal");

    var tamañoCant = cant.length;

    for (var i = 0; i < tamañoCant; i++) 
    {
        var inpC = cant[i];
        var inpP = prec[i];
        var inpS = sub[i];

        inpS.value = inpC.value * inpP.value;
        document.getElementsByName("subtotal")[i].innerHTML = inpS.value;
    }

    calcularTotales();
}

function calcularTotales()
{
    var sub = document.getElementsByName("subtotal");
    var total = 0.0;

    var tamSub = sub.length;

    for (var i = 0; i < tamSub; i++) {
        total += document.getElementsByName("subtotal")[i].value;
    }

    $("#total").html("$ "+ total);
    $("#total_compra").val(total);

    evaluar();
}

function evaluar()
{
    if(detalles > 0)
    {
        $("#btnGuardar").show();
    }
    else
    {
        $("#btnGuardar").hide();
        cont = 0;
    }
}

function eliminarDetalle(indice)
{
    $("#fila" + indice).remove();

    detalles -= 1;

    calcularTotales();
}

init();
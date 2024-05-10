<?php
  //Activacion de almacenamiento en buffer
  ob_start();
  //iniciamos las variables de session
  session_start();

  if(!isset($_SESSION["nombre"]))
  {
    header("Location: login.html");
  }

  else  //Agrega toda la vista
  {
    require 'header.php';

    if($_SESSION['compras'] == 1)
    {
?>

<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title">Movimientos <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true,true)"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tblistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Opciones</th>
                            <th>Fecha</th>
                            <th>Origen</th>
                            <th>Usuario</th>
                            <th>Documento</th>
                            <th>Numero Doc.</th>
                            <th>Estado</th>
                          </thead>
                          <tbody>

                          </tbody>
                          <tfoot>
                            <th>Opciones</th>
                            <th>Fecha</th>
                            <th>Origen</th>
                            <th>Usuario</th>
                            <th>Documento</th>
                            <th>Numero Doc.</th>
                            <th>Estado</th>
                          </tfoot>
                        </table>
                    </div>
					<div class="panel-body" id="listadocabecera">
						
						<div class="form-group col-lg-4 col-md-4 col-sm-8 col-xs-12">
                            <label>Entidad:</label>
                            <input type="text" class="form-control" name="nombre_persona" id="nombre_persona" maxlength="7" placeholder="Proveedor">
                        </div>
                        <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                            <label>Tipo de Comprobante:</label>
                            <input type="text" class="form-control" name="tipo_comprobante_cab" id="tipo_comprobante_cab" maxlength="10" placeholder="Tipo">
                        </div>
						<div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Serie:</label>
                            <input type="text" class="form-control" name="serie_comprobante_cab" id="serie_comprobante_cab" maxlength="7" placeholder="Serie">
                        </div>
                        <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
							<label>Numero:</label>
                            <input type="text" class="form-control" name="num_comprobante_cab" id="num_comprobante_cab" maxlength="10" placeholder="Numero">
                        </div>
						<div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
							<label>Fecha:</label>
                            <input type="text" class="form-control" name="fecha_hora_cab" id="fecha_hora_cab" maxlength="10" placeholder="Fecha">
                        </div>
						<!--
						<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-danger" onclick="cancelarform()" type="button" id="btnVolver"><i class="fa fa-arrow-circle-left"></i> Volver</button>
                        </div>
						-->


					</div>
                    <div class="panel-body" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">
                          <!--
							<div class="form-group col-lg-4 col-md-4 col-sm-8 col-xs-12">
                            <label>Proveedor:</label>
                            <input type="text" class="form-control" name="nombre_persona" id="nombre_persona" maxlength="7" placeholder="Proveedor">
                          </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                            <label>Tipo de Comprobante:</label>
                            <input type="text" class="form-control" name="tipo_comprobante_cab" id="tipo_comprobante_cab" maxlength="10" placeholder="Tipo">
                          </div>
						  <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Serie:</label>
                            <input type="text" class="form-control" name="serie_comprobante_cab" id="serie_comprobante_cab" maxlength="7" placeholder="Serie">
                           </div>
                          <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
							<label>Numero:</label>
                            <input type="text" class="form-control" name="num_comprobante_cab" id="num_comprobante_cab" maxlength="10" placeholder="Numero">
                          </div>
						  <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
							<label>Fecha:</label>
                            <input type="text" class="form-control" name="fecha_hora_cab" id="fecha_hora_cab" maxlength="10" placeholder="Fecha">
                          </div>
						  -->
						  <!--
						  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<button class="btn btn-danger" onclick="cancelarform()" type="button" id="btnVolver"><i class="fa fa-arrow-circle-left"></i> Volver</button>
                          </div>
						  -->




						  <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                              <a data-toggle="modal" href="#myModal" >
                                <button id="btnAgregarArt" type="button" class="btn btn-primary" onclick="consultarDetalle()">
                                  <span class="fa fa-plus"></span>
                                  Agregar Articulos
                                </button>
                              </a>
                          </div>
							
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                              <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                                <thead style="background-color:#A9D0F5">
                                  <th>Opciones</th>
								  <th>Codigo</th>
                                  <th>Articulo</th>
								  <th>Descripcion</th>
								  <th>Categoria</th>
                                  <th>Cantidad</th>
                                  <th>Ubicación Origen</th>
                                  <th>Ubicación Destino</th>
                                </thead>
                                <tfoot>
                                  <th></th>
                                  <th></th>
								  <th></th>
								  <th></th>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                                </tfoot>
                                <tbody>

                                </tbody>
                              </table>
                          </div>
                          
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                            <button class="btn btn-danger" onclick="cancelarform()" type="button" id="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                          </div>
                        </form>
                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->

  <!--VENTANA MODAL-->
     <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog" style="width: 65% !important;">
         <div class="modal-content">
           <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
             <h4 class="modal-title">Seleccione un articulo</h4>
           </div>

           <div class="modal-body">
             <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
               <thead>
                 <th>Opciones</th>
                 <th>Nombre</th>
				 <th>Descripcion</th>
                 <th>Categoria</th>
                 <th>Codigo</th>
				 <th>Ubicación</th>
                 <th>Stock</th>
                 <th>Imagen</th>
               </thead>
               <tbody>

               </tbody>
               <tfoot>
                 <th>Opciones</th>
                 <th>Nombre</th>
				 <th>Descripcion</th>
                 <th>Categoria</th>
                 <th>Codigo</th>
				 <th>Ubicación</th>
                 <th>Stock</th>
                 <th>Imagen</th>
               </tfoot>
             </table>
           </div>

           <div class="modal-footer">
             <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
           </div>

         </div>
       </div>
     </div>
  <!--FIN VENTANA MODAL-->


<?php
  
  } //Llave de la condicion if de la variable de session

  else
  {
    require 'noacceso.php';
  }

  require 'footer.php';
?>
<script src="./scripts/movimiento.js"></script>

<?php
  }
  ob_end_flush(); //liberar el espacio del buffer
?>
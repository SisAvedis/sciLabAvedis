<?php
  if(strlen(session_id()) < 1) //Si la variable de session no esta iniciada
  {
    session_start();
  } 
  if(isset($_COOKIE['sistema']))
  if($_COOKIE['sistema'] != 'sciLab'){
    session_unset(); //Limpiamos las variables de sesion
    session_destroy(); //Destriumos la sesion
    if (isset($_SERVER['HTTP_COOKIE'])) {
      $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
      foreach($cookies as $cookie) {
          $parts = explode('=', $cookie);
          $name = trim($parts[0]);
          setcookie($name, '', time()-1000);
          setcookie($name, '', time()-1000, '/');
      }
  }
    header("Location: login.html");
  }

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SCI | Laboratorio</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta http-equiv="cache-control" content="no-store, no-cache, must-revalidate" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../public/css/font-awesome.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../public/css/_all-skins.css">
    <link rel="apple-touch-icon" href="../public/img/apple-touch-icon.png">
    <link rel="shortcut icon" href="../public/img/avedis_favicon.ico">

    <!--DATATABLES-->
    <link rel="stylesheet" href="../public/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../public/datatables/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../public/datatables/responsive.dataTables.min.css">
    
    <link rel="stylesheet" href="../public/css/bootstrap-select.min.css">
    

  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <header class="main-header">

        <!-- Logo -->
        <a href="index2.html" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>SI</b>SCI</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>SCI Lab</b></span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Navegación</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?php echo $_SESSION['nombre']; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="img-circle" alt="User Image">
                    <p>
                      Sistemas
                      <small>Avedis</small>
                    </p>
                  </li>
                  
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    
                    <div class="pull-right">
                      <a href="../ajax/usuario.php?op=salir" class="btn btn-default btn-flat">Cerrar</a>
                    </div>
                  </li>
                </ul>
              </li>
              
            </ul>
          </div>

        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">       
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header"></li>
            <?php
              if($_SESSION['escritorio'] == 1)
              {
                echo 
                '<li>
                  <a href="escritorio.php">
                    <i class="fa fa-tasks"></i> <span>Escritorio</span>
                  </a>
                </li>';
              }

              if($_SESSION['almacen'] == 1)
              {
                echo 
                '<li class="treeview">
                    <a href="#">
                      <i class="fa fa-laptop"></i>
                      <span>Depósito</span>
                      <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                      <li><a href="movimiento.php"><i class="fa fa-circle-o"></i> Movimientos</a></li>
					  <li><a href="ubicacion.php"><i class="fa fa-circle-o"></i> Ubicaciones</a></li>
                    </ul>
                  </li>'
                 ;
              }
			  if($_SESSION['articulo'] == 1)
              {
                echo 
                '<li class="treeview">
                    <a href="#">
                      <i class="fa fa-laptop"></i>
                      <span>Artículo</span>
                      <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                      <li><a href="articulo.php"><i class="fa fa-circle-o"></i> Artículos</a></li>
                      <li><a href="categoria.php"><i class="fa fa-circle-o"></i> Categorías</a></li>
					  <li><a href="unidad.php"><i class="fa fa-circle-o"></i> Unidades de Medida</a></li>
                    </ul>
                  </li>'
                 ;
              }
              if($_SESSION['compras'] == 1)
              {
                echo 
                '<li class="treeview">
                    <a href="#">
                      <i class="fa fa-th"></i>
                      <span>Ingresos</span>
                      <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                      <li><a href="ingreso.php"><i class="fa fa-circle-o"></i> Entradas</a></li>
					  <!--<li><a href="devolucionp.php"><i class="fa fa-circle-o"></i> Devoluciones</a></li>-->
					  <li><a href="proveedor.php"><i class="fa fa-circle-o"></i> Proveedores</a></li>
                    </ul>
                  </li>'
                 ;
              }
              if($_SESSION['ventas'] == 1)
              {
                echo 
                '<li class="treeview">
                    <a href="#">
                      <i class="fa fa-shopping-cart"></i>
                      <span>Egresos</span>
                      <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                      <li><a href="venta.php"><i class="fa fa-circle-o"></i> Salidas</a></li>
					  <!--<li><a href="devolucion.php"><i class="fa fa-circle-o"></i> Devoluciones</a></li>-->
            ';
            if($_SESSION['articulo'] == 1)
            {
            echo'<li><a href="cliente.php"><i class="fa fa-circle-o"></i> Empleados</a></li>';
            }
            echo'<!--<li><a href="sector.php"><i class="fa fa-circle-o"></i> Sectores</a></li>-->
                    </ul>
                  </li>'
                 ;
              }
              if($_SESSION['acceso'] == 1)
              {
                echo 
                '<li class="treeview">
                    <a href="#">
                      <i class="fa fa-folder"></i> <span>Acceso</span>
                      <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                      <li><a href="usuario.php"><i class="fa fa-circle-o"></i> Usuarios</a></li>
                      <li><a href="permiso.php"><i class="fa fa-circle-o"></i> Permisos</a></li>
                      
                    </ul>
                  </li>'
                 ;
              }
              if($_SESSION['consultac'] == 1)
              {
                echo 
                '<li class="treeview">
                    <a href="#">
                      <i class="fa fa-bar-chart"></i> <span>Consulta Ingresos</span>
                      <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                      <li><a href="comprasfecha.php"><i class="fa fa-circle-o"></i> Consulta Ingresos</a></li>                
                    </ul>
                  </li>'
                 ;
              }

              if($_SESSION['consultav'] == 1)
              {
                echo 
                '<li class="treeview">
                    <a href="#">
                      <i class="fa fa-bar-chart"></i> <span>Consulta Egresos</span>
                      <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                      <li><a href="ventasfechacliente.php"><i class="fa fa-circle-o"></i> Ordenes Fecha Operario</a></li>
					  <li><a href="entregasfechaoperario.php"><i class="fa fa-circle-o"></i> Articulos (E) Fecha Operario</a></li>
					  <li><a href="entregasfechaoperarioagrupado.php"><i class="fa fa-circle-o"></i> Articulos(E) Fecha Operario (Agr)</a></li>
					  <li><a href="devolucionesfechaoperario.php"><i class="fa fa-circle-o"></i> Articulos (D) Fecha Operario</a></li>
					  <li><a href="devolucionesfechaoperarioagrupado.php"><i class="fa fa-circle-o"></i> Articulos(D) Fecha Operario (Agr)</a></li>	
					</ul>
                  </li>'
                 ;
              }
			 
			  if($_SESSION['consultas'] == 1)
              {
                echo 
                '<li class="treeview">
                    <a href="#">
                      <i class="fa fa-bar-chart"></i> <span>Consulta Stock</span>
                      <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                      <li><a href="stock.php"><i class="fa fa-circle-o"></i> Stock Ubicacion</a></li>
					  <!--<li><a href="movimientosarticulo.php"><i class="fa fa-circle-o"></i> Movimientos Articulo Ubicacion</a></li>-->
                    </ul>
                  </li>'
                 ;
              }
            ?>                                
          
            <li>
              <a href="#">
                <i class="fa fa-plus-square"></i> <span>Ayuda</span>
                <small class="label pull-right bg-red">PDF</small>
              </a>
            </li>
            <li>
              <a href="#">
                <i class="fa fa-info-circle"></i> <span>Acerca De...</span>
                <small class="label pull-right bg-yellow">IT</small>
              </a>
            </li>
                        
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
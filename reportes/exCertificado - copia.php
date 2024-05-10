<?php
  //Activacion de almacenamiento en buffer
  ob_start();
  
  if(strlen(session_id()) < 1) //Si la variable de session no esta iniciada
  {
      session_start();
  } 

  if(!isset($_SESSION["nombre"]))
  {
      echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
    }
    
    else  //Agrega toda la vista
    {
        
        if($_SESSION['escritorio'] == 1)
        {
            //Incluimos archivo Factura.php
            require 'Factura.php';
            
            //Datos
            $logo = "VectorAvedis.png";
            $ext_logo = "png";
            $empresa = "avedis s_logo.png";
            $ext_empresa = "png";
            $documento = "Tecno Agro Vial S.A.";
            $direccion = "Ruta de la Tradición 4699 - Buenos Aires";
            $telefono = "011-4693-4008 / 4009 / 5226";
            $email = "info@avedis.com.ar";
            $codigoPostal = "(B1839FQF) 9 DE ABRIL - E. Echeverría - Bs. As.";
            
            //Obtenemos los datos de la cabecera
            require_once '../modelos/Certificados.php';
        $certificado = new Certificado();
        
        $rsptav = $certificado->certificadoCabecera($_GET['id']);
        
        //Recorremos los datos obtenidos
        $regv = $rsptav->fetch_object();
        
        $pdf = new PDF_Invoice('P','mm','A4');
        $contador = 0;
        while($contador < 2) {
        $pdf->AddPage();
        //Enviamos los datos de la empresa al metodo addSociete de la clase factura
        //Para ubicar los datos correspondientes
        if($contador == 0  && $regv->partida != '-'){
            $pdf->addAdvertenciaRotulo(
            );
        }
        $pdf->addAdvertenciaDuplicado($contador
            );

        $pdf->addSociete(
            utf8_decode($empresa),
            $ext_empresa,
            $documento."\n".
            utf8_decode("Dirección: ").utf8_decode($direccion)."\n".
            utf8_decode($codigoPostal)."\n".
            utf8_decode("Telefax: ").utf8_decode($telefono)."\n".
            "Email: ".$email
            ,
            $logo,
            $ext_logo,
            
        );
        
        if($regv->estado == 'Anulado'){
            $pdf->addAnulacion();
        }


        if($regv->partida != '-'){


        
        $pdf->fact_dev(
            utf8_decode("CERTIFICADO A Nº "),
            "$regv->num_comprobante"
        );
    }else{
        $pdf->fact_dev(
            utf8_decode("CERTIFICADO B Nº "),
            "$regv->num_comprobante"
        );
        
    }
    
    $pdf->temporaire( "" ); //Marca de Agua
    $pdf->addDate($regv->fecha);
    
    $pdf->addCabecera($regv->fecha, $regv->partida, $regv->num_comprobante, utf8_decode($regv->cliente), $regv->num_pcpi,  strtoupper($regv->localidad),$regv->tipo_certificado);
    //Establecemos las columnas que va a tener la seccion donde mostramos los detalles de la venta
    
        //Actualizamos el valos de la coordenada "y", que sera la ubicacion desde donde empezaremos a mostrar los datos
        //Obtenemos todos los detalles de la venta actual
        
        //   while($regd = $rsptav->fetch_object())
        //   {
            if($regv->partida == '-'){
                $cols=array(
                    utf8_decode("DETERMINACIÓN")=>92.5,
                    // "RESULTADOS"=>61.6,
                    "ESPECIFICACIONES"=>92.5
                    );
                    $pdf->addCols($cols, $regv->partida);
            
                    $cols = array(
                        utf8_decode("DETERMINACIÓN")=>"C", //Alineacion (Left)
                        // "RESULTADOS"=>"C",
                        "ESPECIFICACIONES"=>"C"
                    );
                    $pdf->addLineFormat($cols);
                    $pdf->addLineFormat($cols);
            
    
                $y = 80;
                $line2 = array(
                    utf8_decode("DETERMINACIÓN")=>utf8_decode("OXÍGENO"),
                    // "RESULTADOS"=>utf8_decode('')."",
                    "ESPECIFICACIONES"=>"Mayor o igual a 99.5% v/v O2"
                );
                $size = $pdf->addLine($y,$line2, 'B');
                $y += $size + 4;
                $line2 = array(
                    utf8_decode("DETERMINACIÓN")=>utf8_decode("CO (Monóxido de Carbono)"),
                    // "RESULTADOS"=>utf8_decode("")."",
                    "ESPECIFICACIONES"=>"Menor a 5 ppm"
                );
                $size = $pdf->addLine($y,$line2, 'B');
                $y += $size + 4;
                $line2 = array(
                    utf8_decode("DETERMINACIÓN")=>utf8_decode("CO2 (Dióxido de Carbono)"),
                    // "RESULTADOS"=>utf8_decode("")."",
                    "ESPECIFICACIONES"=>"Menor a 300 ppm"
                );
                $size = $pdf->addLine($y,$line2, 'B');
                $y += $size + 4;
                $line2 = array(
                    utf8_decode("DETERMINACIÓN")=>"Humedad",
                    // "RESULTADOS"=>utf8_decode("")."",
                    "ESPECIFICACIONES"=>"Menor a 67 ppm"
                );
                $size = $pdf->addLine($y,$line2, 'B');
                $y += $size + 4;
                $line2 = array(
                    utf8_decode("DETERMINACIÓN")=>utf8_decode(" "),
                    // "RESULTADOS"=>"",
                    "ESPECIFICACIONES"=>"No detecta"
                );
                $size = $pdf->addLine($y,$line2, 0);
                $y += $size + 5;

                // $pdf->addColoured();
            }
            else{
                $cols=array(
                    utf8_decode("DETERMINACIÓN")=>61.6,
                    "RESULTADOS"=>61.6,
                    "ESPECIFICACIONES"=>61.6
                    );
                    $pdf->addCols($cols, $regv->partida);
            
                    $cols = array(
                        utf8_decode("DETERMINACIÓN")=>"C", //Alineacion (Left)
                        "RESULTADOS"=>"C",
                        "ESPECIFICACIONES"=>"C"
                    );
                    $pdf->addLineFormat($cols);
                    $pdf->addLineFormat($cols);
            

                $y = 95;
            $line2 = array(
                utf8_decode("DETERMINACIÓN")=>utf8_decode("OXÍGENO"),
                "RESULTADOS"=>utf8_decode("$regv->O2")." %",
                "ESPECIFICACIONES"=>"Mayor o igual a 99.5% v/v O2"
            );
            $size = $pdf->addLine($y,$line2, 'B');
            $y += $size + 4;
            $line2 = array(
                utf8_decode("DETERMINACIÓN")=>utf8_decode("CO (Monóxido de Carbono)"),
                "RESULTADOS"=>utf8_decode($regv->CO)." ppm",
                "ESPECIFICACIONES"=>"Menor a 5 ppm"
            );
            $size = $pdf->addLine($y,$line2, 'B');
            $y += $size + 4;
            $line2 = array(
                utf8_decode("DETERMINACIÓN")=>utf8_decode("CO2 (Dióxido de Carbono)"),
                "RESULTADOS"=>utf8_decode($regv->CO2)." ppm",
                "ESPECIFICACIONES"=>"Menor a 300 ppm"
            );
            $size = $pdf->addLine($y,$line2, 'B');
            $y += $size + 4;
            $line2 = array(
                utf8_decode("DETERMINACIÓN")=>"Humedad",
                "RESULTADOS"=>utf8_decode($regv->H2O)." ppm",
                "ESPECIFICACIONES"=>"Menor a 67 ppm"
            );
            $size = $pdf->addLine($y,$line2, 'B');
            $y += $size + 4;
            $line2 = array(
                utf8_decode("DETERMINACIÓN")=>utf8_decode(" "),
                "RESULTADOS"=>"-",
                "ESPECIFICACIONES"=>"No detecta"
            );
            $size = $pdf->addLine($y,$line2, 0);
            $y += $size + 5;
        }

        //   }

          //Recorremos los datos obtenidos
          
          $pdf->addDescriptionText(
             utf8_decode("OLOR: no detectable"),
             utf8_decode("Ensayos de identificación: positivo"),
             utf8_decode("El producto fue obtenido por licuefacción del aire"),
             utf8_decode("y cumple con especificaciones descriptas"),
             utf8_decode("en Farmacopea Nacional Argentina"),
               utf8_decode("6ta Edición de 1978."),
               $regv -> partida
             
         );
          if($regv->partida != '-'){
     // $rsptad = $certificado->certificadoCabecera($_GET['id']);
     $pdf->addOperadorAdresse(
        utf8_decode($regv->analista)
        
    );
    


    $pdf->addVencimientoAdresse(
        utf8_decode($regv->fechaVencimiento)
        
    );
    $pdf->addSello(
        utf8_decode("TECNO AGRO VIAL\nFarm. Andrea F. Campos\nMP 15197\nDT Planta Bs.As.")
    );}
    else{}
    
    $pdf->addChoferAdresse(
       utf8_decode($regv->chofer), $regv->cisterna
       
   );
   $contador += 1;
    } 
$nombreArchivo = "Certificado ".strval($regv->num_comprobante).".pdf";

    $pdf->Output($nombreArchivo, "I");
    
    }
    else
    {
        echo 'No tiene permiso para visualizar el reporte';
    }


   }
   ob_end_flush(); //liberar el espacio del buffer
?>
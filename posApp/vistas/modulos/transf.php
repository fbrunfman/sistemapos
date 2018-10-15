<script>
  
  $(document).ready(function(){

    $(".dt-buttons").children().addClass("btn btn-success");

    $("#lista").children().removeClass();
    $("#transf").addClass("active");

  });

</script>



<div class="content-wrapper">
    <section class="content-header">
      <h1>
        Administrar transferencias
  
      </h1>
      <ol class="breadcrumb">
        <li><a href="inicio"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
        <li class="active">Transferencias</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">

        <div class="box-header with-border">

<button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarTransf">
            
            Agregar transferencia

          </button>

        </div>
      </div>



      <div class="box">

        <div class="box-header with-border">

          <button class="btn btn-primary" data-toggle="modal" data-target="#modalImputarTransf">
            
            Imputar transferencia

          </button>


        </div>


        
        
      </div>

      <div class="box">

        <div class="box-header with-border">

          <button class="btn btn-primary" data-toggle="modal" data-target="#modalConsultaTransf">
            
            Consultar transferencias

          </button>

        <div class="box-body">

          <?php 

              function formatearFecha($fecha) {
                   $year =  substr($fecha, 0, 4);
                   $mes = substr($fecha, 5, 2);
                   $dia = substr($fecha, 8, 2);

                   return $dia . "-" . $mes . "-" . $year;
                   
                  }

              $servername = "localhost";
              $username = "root";
              $password = "root";
              $dsn_Options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

              try {

                  $conn = new PDO("mysql:host=$servername;dbname=sistemapos", $username, $password, $dsn_Options);
                  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                  
                  if (isset($_POST["agregar"])) {

                    $query = "INSERT INTO transferencia (nombre, `cuit cuil`, cbu, concepto, `numero de factura` ,`tipo de factura`, `importe total`, realizado) VALUES (:nombre, :cuitCuil, :cbu, :concepto, :numFactura, :tipoFactura, :importeTotal, :realizado)";
                    $stmt = $conn->prepare($query);
                    
                    $stmt->bindParam(":nombre", $_POST["nombre"]);
                    $stmt->bindParam(":cuitCuil", $_POST["cuitCuil"]);
                    $stmt->bindParam(":cbu", $_POST["cbu"]);
                    $stmt->bindParam(":concepto", $_POST["concepto"]);
                    $stmt->bindParam(":numFactura", $_POST["numFactura"]);
                    $stmt->bindParam(":tipoFactura", $_POST["tipoFactura"]);
                    $stmt->bindParam(":importeTotal", $_POST["importeTotal"]);
                    $stmt->bindParam(":realizado", $_POST["realizado"]);

                    if($_POST["fecha"]){
                      $queryFecha = "INSERT INTO transferencia (fecha) VALUES (:fecha)";
                      $stmt1->prepare($queryFecha);
                      $stmt1->bindParam(":fecha", $_POST["fecha"]);
                      $stmt1->execute();
                    }
                    

                    $stmt->execute(); 

                    echo "<script>swal('Transferencia agregada exitosamente!')</script>";

                  }

                 if (isset($_POST["imputar"])) {

                    $nombreImputar = $_POST["nombreImputar"];
                    $numFacturaImputar = $_POST["numFacturaImputar"];
                   

                    $queryActualizar = "UPDATE transferencia SET realizado = 'si' WHERE nombre ='" . $nombreImputar . "' AND `numero de factura` = '" . $numFacturaImputar . "'";

                    $stmt = $conn->prepare($queryActualizar);
                    $stmt->execute();


                    echo "<script>swal('¡Pago de transferencia agregado exitosamente!')</script>";
                               
                  }
                
                  if (isset($_POST["consultar"])) {
                    
                    
                    $nombreConsulta = $_POST["nombreConsulta"];
                    $numFactura = $_POST["numFactura"];
                    $desde = $_POST["desde"];
                    $hasta = $_POST["hasta"];
                    $realizado = $_POST["realizado"];

                    
                    if ($desde == '' & $hasta == '') {
                      $queryTransf = "SELECT * FROM transferencia";
                   
                    } else if ($desde == ''){
                      $queryTransf = "SELECT * FROM transferencia WHERE fecha <= '" . $hasta . "'";
                    
                    } else if ($hasta == '') {
                      $queryTransf = "SELECT * FROM transferencia WHERE fecha >= '" . $desde . "'";
                   
                    } else {
                      $queryTransf = "SELECT * FROM transferencia WHERE fecha BETWEEN '" . $desde . "' AND '" . $hasta . "'";
                    
                    } 

                    if ($nombreConsulta !== '') {


                      if (strlen($queryTransf) == 26) {

                        $queryTransf .= " WHERE nombre = '" . $nombreConsulta . "'";

                      } else {

                        $queryTransf .= " AND nombre = '" . $nombreConsulta . "'";

                      }

                    }

                    
                    $stmt1 = $conn->prepare($queryTransf);
                    $stmt1->execute();
                    $resultTransf = $stmt1->fetchAll();


                    $consulta = '<table class="table table-bordered table-striped tabla table-responsive" id="idTabla">
                    <thead>
                    <tr>
                      <th>Nombre</th>
                      <th>CUIT/CUIL</th>
                      <th>CBU</th>
                      <th>Concepto</th>
                      <th>N&uacute;mero de Factura</th>
                      <th>Tipo de factura</th>
                      <th>Fecha de transferencia</th>
                      <th>Importe total</th>
                      <th>Realizado</th>
                    </tr>
                    </thead>
                    <tbody>';


                    for ($i=0; $i < count($resultTransf) ; $i++) { 

                      $yesorno = "";
                        if ($resultTransf[$i]["realizado"] == "si") {
                          $yesorno = "S&iacute;";
                        } else {
                          $yesorno = "No";
                        }
                      
                      if ($realizado == "si" && $resultTransf[$i]["realizado"] == "si"){

                        $consulta .= "<tr><td>" . $resultTransf[$i]["nombre"] . "</td><td>" . $resultTransf[$i]["cuit cuil"] . "</td><td>" . $resultTransf[$i]["cbu"] . "</td><td>" . $resultTransf[$i]["concepto"] . "</td><td>" . $resultTransf[$i]["numero de factura"] . "</td><td>" . $resultTransf[$i]["tipo de factura"] . "</td><td>" . formatearFecha($resultTransf[$i]["fecha"]) . "</td><td>" . $resultTransf[$i]["importe total"] . "</td><td>" . $yesorno .  "</td></tr>";

                      } else if ($realizado == "no" && $resultTransf[$i]["realizado"] == "no") {

                        $consulta .= "<tr><td>" . $resultTransf[$i]["nombre"] . "</td><td>" . $resultTransf[$i]["cuit cuil"] . "</td><td>" . $resultTransf[$i]["cbu"] . "</td><td>" . $resultTransf[$i]["concepto"] . "</td><td>" . $resultTransf[$i]["numero de factura"] . "</td><td>" . $resultTransf[$i]["tipo de factura"] . "</td><td>" . formatearFecha($resultTransf[$i]["fecha"]) . "</td><td>" . $resultTransf[$i]["importe total"] . "</td><td>" . $yesorno .  "</td></tr>";

                      } else if ($realizado == "todas") {


                        $consulta .= "<tr><td>" . $resultTransf[$i]["nombre"] . "</td><td>" . $resultTransf[$i]["cuit cuil"] . "</td><td>" . $resultTransf[$i]["cbu"] . "</td><td>" . $resultTransf[$i]["concepto"] . "</td><td>" . $resultTransf[$i]["numero de factura"] . "</td><td>" . $resultTransf[$i]["tipo de factura"] . "</td><td>" . formatearFecha($resultTransf[$i]["fecha"]) . "</td><td>" . $resultTransf[$i]["importe total"] . "</td><td>" . $yesorno .  "</td></tr>";
                      }

                    }

                    echo $consulta .= '</tbody></table>';

                  }

                
                
                  
                  
                }
                  
                 catch(PDOException $e)
                 {
                 echo $message = $e->getMessage();
                 }
                  
                      
                  
                  
              ?>


      


        </div>

        
      </div>

    </section>

</div>





<div id="modalAgregarTransf" class="modal fade" role="dialog">

    <div class="modal-dialog">

      <!-- Modal content-->
     <div class="modal-content">

        <form role="form" method="post" id="agregar">

        <div class="modal-header" style="background: #3c8dbc; color: white;">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar Transferencia</h4>

        </div>

    
    <div class="modal-body">
      
      
      <div class="box-body">

        
        <div class="form-group">

          <div class="input-group">

            <span class="input-group-addon"><i class="fa fa-user"></i></span>

            <input type="text" name="nombre" class="form-control" placeholder="Ingresar nombre del proveedor" required>

          </div>

        </div>
       
       
        <div class="form-group">

          <div class="input-group">

            <span class="input-group-addon"><i class="fa fa-receipt"></i></span>

            <input type="text" name="cuitCuil" class="form-control" placeholder="Ingresar n&uacute;mero de Cuit">

          </div>

        </div>

        
        <div class="form-group">

          <div class="input-group">

            <span class="input-group-addon"><i class="fa fa-receipt"></i></span>

            <input type="text" name="cbu" class="form-control" placeholder="Ingresar  n&uacute;mero de CBU">

          </div>

        </div>
       
       
        <div class="form-group">

          <div class="input-group">

            <span class="input-group-addon"><i class="fa fa-file-alt"></i></span>

            <input type="text" name="concepto" class="form-control" placeholder="Ingresar concepto" required>

          </div>

        </div>
        
        
        <div class="form-group">

          <div class="input-group">

            <span class="input-group-addon"><i class="fa fa-receipt"></i></span>

            <input type="text" name="numFactura" class="form-control" placeholder="Ingresar n&uacute;mero de factura" required>

          </div>

        </div>
        
        
        <div class="form-group">

          <div class="input-group">

            <span class="input-group-addon"><i class="fa fa-file-alt"></i></span>

            <input type="text" name="tipoFactura" class="form-control" placeholder="Ingresar tipo de factura" required>

          </div>

        </div>


       
       
        <div class="form-group">

          <div class="input-group">

            <span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>

            <input type="text" name="importeTotal" class="form-control" placeholder="Ingresar importe a pagar" required>

          </div>

        </div>


        Ingresar si la transferencia fue realizada
        <div class="form-group">

            <div class="input-group">
 

               <select name="realizado" form="agregar">

                <option value="si">S&iacute;</option>
                <option value="no">No</option>

              </select> 

            </div>

          </div>



        
        Fecha de realizaci&oacute;n
        <div class="form-group">

          <div class="input-group">

            <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>

            <input type="date" name="fecha" class="form-control"">

          </div>

        </div>



      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

        <button type="submit" name="agregar" class="btn btn-primary">Guardar transferencia</button>

      </div>

      </form>

    </div>



      </div>

    </div>

</div>


<div id="modalConsultaTransf" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post" id="consultar">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Consultar datos de venta</h4>

      </div>



      <div class="modal-body">
        
        <div class="box-body">



          
          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="nombreConsulta" class="form-control" placeholder="Ingresar nombre o dejar en blanco si desea consultar mediante otros par&aacute;metros">

            </div>

          </div>




          <br>

          
          


          Desde

          <br><br>

          <div class="form-group">

            <div class="input-group">

              
              <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>

              <input type="date" name="desde" class="form-control">

            </div>

          </div>

          Hasta

          <br><br>

          <div class="form-group">

            <div class="input-group">


              <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>

              <input type="date" name="hasta" class="form-control">

            </div>

          </div>


          <div class="form-group">

            <div class="input-group">

              

               <select name="realizado" form="consultar">
                <option value="todas">Todas las transferencias</option>
                <option value="si">Solo realizadas</option>
                <option value="no">Solo pendientes</option>
              </select> 

            </div>

          </div>

          

        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

        <button type="submit" name="consultar" class="btn btn-primary">Consultar datos</button>

      </div>

      </form>

    </div>

  </div>
</div>


<div id="modalImputarTransf" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Imputar transferencia</h4>

      </div>

      <div class="modal-body">
        
        <div class="box-body">


          
          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="nombreImputar" class="form-control" placeholder="Ingresar nombre" required>

            </div>

            

          </div>


          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="numFacturaImputar" class="form-control" placeholder="Ingresar n&uacute;mero de factura" required>

            </div>

            

          </div>

        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

        <button type="submit" name="imputar" class="btn btn-primary">Enviar datos</button>

      </div>

      </form>

    </div>

  </div>
</div>



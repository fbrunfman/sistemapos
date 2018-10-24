<script>

  $(document).ready(function(){

    $(".dt-buttons").children().addClass("btn btn-success");

    $("#lista").children().removeClass();
    $("#ventas").addClass("active");


  });
  
  

</script>


<div class="content-wrapper">
    <section class="content-header">
      <h1>
        Administrar ventas
  
      </h1>
      <ol class="breadcrumb">
        <li><a href="inicio"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
        <li class="active">Administrar usuarios</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">

        <div class="box-header with-border">


          <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarVenta">
            
            Agregar venta

          </button>

          </div>
        </div>

        <!-- Default box -->


        <div class="box">

        <div class="box-header with-border">


          <button class="btn btn-primary" data-toggle="modal" data-target="#modalEditarVenta">
            
            Editar venta

          </button>

          </div>
        </div>
      

          <!-- Default box -->
      <div class="box">

        <div class="box-header with-border">

          <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarPago">
            
            Agregar pago

          </button>


        </div>

        
      </div>

      <div class="box">

        <div class="box-header with-border">

          <button class="btn btn-primary" data-toggle="modal" data-target="#modalConsultaVenta">
            
            Consultar venta

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

                  

                  $query = "INSERT INTO venta (cliente_id, concepto, importe, `numero de factura`, `fecha de venta`) VALUES (:cliente_id, :concepto, :importe, :numFactura, :fechaVenta)";
                  $stmt = $conn->prepare($query);
                  $stmt->bindParam(":cliente_id", $_POST["nombre"]);
                  $stmt->bindParam(":concepto", $_POST["concepto"]);
                  $stmt->bindParam(":importe", $_POST["importe"]);
                  $stmt->bindParam(":numFactura", $_POST["numFactura"]);
                  $stmt->bindParam(":fechaVenta", $_POST["fechaVenta"]);

                  $stmt->execute(); 

                  echo "<script>swal('¡Venta realizada exitosamente!')</script>";

               }


               if (isset($_POST["consultar"])) {

                  $nombreConsulta = $_POST["nombreConsulta"];
                  $queVentas = $_POST["queVentas"];
                  $desde = $_POST["desde"];
                  $hasta = $_POST["hasta"];
                  $importes = $_POST["importes"];
                  $ingresosFinales = $_POST["ingresosFinales"];
                  $queryVenta = "";

                  //query a venta


                  if ($desde == '' && $hasta == '') {

                      $queryVenta = "SELECT * FROM venta";

                  } else if ($desde == '') {

                      $queryVenta = "SELECT * FROM venta WHERE `fecha de venta` <= '" . $hasta . "'";

                  } else if ($hasta == '') {

                      $queryVenta = "SELECT * FROM venta WHERE `fecha de venta` >= '" . $desde . "'";

                  } else {

                      $queryVenta = "SELECT * FROM venta WHERE `fecha de venta` BETWEEN '" . $desde . "' AND '" . $hasta . "'";

                  }

                  if ($nombreConsulta !== '') {

                    

                    if (strlen($queryVenta) == 19) {

                      $queryVenta .= " WHERE cliente_id =" . $nombreConsulta;

                    } else {

                      $queryVenta .= " AND cliente_id =" . $nombreConsulta;

                    }

                  }

                  $stmt1 = $conn->prepare($queryVenta);
                  $stmt1->execute();
                  $resultVenta = $stmt1->fetchAll();


                  if ($queVentas == "pagas") {


                    for ($i=0; $i < count($resultVenta) ; $i++) { 
                      
                      if (is_null($resultVenta[$i]["fecha de pago"])){

                        $resultVenta[$i] = NULL;

                      }

                    }

                  } else if ($queVentas == "noPagas") {

                    for ($i=0; $i < count($resultVenta) ; $i++) { 
                      
                      if (!is_null($resultVenta[$i]["fecha de pago"])){

                        $resultVenta[$i] = NULL;

                      }

                    }

                  }

                 

                 $consulta = '<table class="table table-bordered table-striped tabla table-responsive" id="idTabla">
                  <thead>
                  <tr>
                    <th>Nombre de cliente</th>
                    <th>Concepto</th>
                    <th>Importe</th>
                    <th>N&uacute;mero de factura</th>
                    <th>Fecha de venta</th>
                    <th>IVA</th>
                    <th>Seguridad social</th>
                    <th>Impuesto a las ganancias</th>
                    <th>Ingresos brutos</th>
                    <th>Ingreso final</th>
                    <th>Fecha de pago</th>
                  </tr>
                  </thead>
                  <tbody>';

                 $sumaImportes = 0;
                 $sumaIngresosFinales = 0;

                 for ($i=0; $i < count($resultVenta); $i++) { 
                   if (!is_null($resultVenta[$i])) {
                    $queryNombre = "SELECT nombre FROM cliente WHERE id =" . $resultVenta[$i]["cliente_id"];
                    $stmt = $conn->prepare($queryNombre);
                    $stmt->execute();
                    $resultNombre = $stmt->fetchALL();
                    $nombreCliente = $resultNombre[0][0];

                    $consulta .= "<tr><td>" . $nombreCliente . "</td><td>" . $resultVenta[$i]["concepto"] . "</td><td>" . $resultVenta[$i]["importe"] . "</td><td>" . $resultVenta[$i]["numero de factura"] . "</td><td>" .  formatearFecha($resultVenta[$i]["fecha de venta"]) . "</td><td>" . $resultVenta[$i]["iva"] . "</td><td>" . $resultVenta[$i]["suss"] . "</td><td>" . $resultVenta[$i]["ganancias"] . "</td><td>" . $resultVenta[$i]["iibb"] . "</td><td>" . $resultVenta[$i]["ingreso final"] . "</td><td>" . formatearFecha($resultVenta[$i]["fecha de pago"]) . "</td></tr>";
                   }      
                   
                 }

                 $consulta .= '</tbody></table>';

                 echo $consulta;
                 
               }

               if (isset($_POST["editar"])) {

                  $id = $_POST["nombre"];
                  $numFactura = $_POST["numFactura"];
                  $idNew = $_POST["nombreNew"];
                  $conceptoNew = $_POST["conceptoNew"];
                  $numFacturaNew = $_POST["numFacturaNew"];
                  $importeNew = $_POST["importeNew"];
                  $fechaNew = $_POST["fechaNew"];
                  $ivaNew = $_POST["ivaNew"];
                  $sussNew = $_POST["sussNew"];
                  $gananciasNew = $_POST["gananciasNew"];
                  $iibbNew = $_POST["iibbNew"];
                  $ingresoFinalNew = $_POST["ingresoFinalNew"];
                  $fechaPagoNew = $_POST["fechaPagoNew"];


                  if ($ivaNew == "") {
                    $ivaNew = 0;
                  }

                  if ($sussNew == "") {
                    $sussNew = 0;
                  }

                  if ($gananciasNew == "") {
                    $gananciasNew = 0;
                  }

                  if ($iibbNew == "") {
                    $iibbNew = 0;
                  }

                  if ($ingresoFinalNew == "") {
                    $ingresoFinalNew = 0;
                  }

                  if (!$fechaPagoNew) {
                    $queryEditar = "UPDATE venta SET cliente_id = ". $idNew .", concepto = '" . $conceptoNew . "', `numero de factura` ='" . $numFacturaNew . "', importe = " . $importeNew . ", `fecha de venta` = '" . $fechaNew . "', iva = " . $ivaNew . ", suss = " . $sussNew . ", ganancias = " . $gananciasNew . ", iibb = " . $iibbNew . ", `ingreso final` = " . $ingresoFinalNew . ", `fecha de pago` = NULL WHERE cliente_id = " . $id . " AND `numero de factura` = '" . $numFactura . "'";
                  } else {
                    $fechaPagoNew = "'" .  $fechaPagoNew . "'";

                    $queryEditar = "UPDATE venta SET cliente_id = ". $idNew .", concepto = '" . $conceptoNew . "', `numero de factura` ='" . $numFacturaNew . "', importe = " . $importeNew . ", `fecha de venta` = '" . $fechaNew . "', iva = " . $ivaNew . ", suss = " . $sussNew . ", ganancias = " . $gananciasNew . ", iibb = " . $iibbNew . ", `ingreso final` = " . $ingresoFinalNew . ", `fecha de pago` = " . $fechaPagoNew . " WHERE cliente_id = " . $id . " AND `numero de factura` = '" . $numFactura . "'";
                  }

                  var_dump($queryEditar);

                  $stmt = $conn->prepare($queryEditar);
                  $stmt->execute();

                  echo "<script>swal('¡Edición realizada exitosamente!')</script>";

               }


               if (isset($_POST["agregarPago"])) {


                  $query = "UPDATE venta SET iva = " . $_POST["iva"] . ", suss = " . $_POST["suss"] . ", ganancias = " . $_POST["ganancias"] . ", iibb = " . $_POST["iibb"] . ", `ingreso final` = " . $_POST["ingresoFinal"] . ", `fecha de pago`= '" . $_POST["fechaPago"] . "' WHERE cliente_id = " . $_POST["nombre"] . " AND `numero de factura` ='" . $_POST["numFactura"] . "'";

                  $stmt = $conn->prepare($query);
                  $stmt->execute(); 

                  echo "<script>swal('¡Pago agregado exitosamente!')</script>";
                 
               }

              }

          catch(PDOException $e)
              {
              echo $message = $e->getMessage();
              }

           ?>

          
        
        </div>


      </div>
    </div>

    </section>

  </div>

<!-- Modal -->

<div id="modalAgregarVenta" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post" id="agregar">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Agregar venta</h4>

      </div>

      <div class="modal-body">
        
        <div class="box-body">
          
          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <select class="form-control" name="nombre" id="agregar">
                <option value="">Seleccionar cliente</option>
                <?php 
                  require_once("conexion.php");
                  conectar();
                  global $conn;
                  $query = "SELECT * FROM cliente";
                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  $result = $stmt->fetchALL();

                  for ($i=0; $i < count($result); $i++) { 
                    echo "<option value='" . $result[$i]["id"] . "'>" . $result[$i]["nombre"] . "</option>";
                  }


                ?>
              </select>

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

              <span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>

              <input type="number" name="importe" class="form-control" placeholder="Ingresar importe" required>

            </div>

            
          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-receipt"></i></span>

              <input type="text" name="numFactura" class="form-control" placeholder="Ingresar n&uacute;mero de factura" required>

            </div>

            
          </div>


          Fecha de venta

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>

              <input type="date" name="fechaVenta" class="form-control" placeholder="Ingresar fecha de venta" required>

            </div>

            
          </div>

        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

        <button type="submit" name="agregar" class="btn btn-primary">Guardar venta</button>

      </div>

      </form>

    </div>

  </div>
</div>


<div id="modalEditarVenta" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post" id="editar">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Editar venta</h4>

      </div>

      <div class="modal-body">
        
        <div class="box-body">

          
          Ingres&aacute; los datos de la venta que quieras editar <br><br>


          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <select class="form-control" name="nombre" id="editar">
                <option value="">Seleccionar cliente</option>
                <?php 
                  require_once("conexion.php");
                  conectar();
                  global $conn;
                  $query = "SELECT * FROM cliente";
                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  $result = $stmt->fetchALL();

                  for ($i=0; $i < count($result); $i++) { 
                    echo "<option value='" . $result[$i]["id"] . "'>" . $result[$i]["nombre"] . "</option>";
                  }


                ?>
              </select>

            </div>

          </div>


          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="numFactura" class="form-control" placeholder="Ingresar n&uacute;mero de factura">

            </div>

          </div>


          <br><br>


          Ingres&aacute; todos los datos corregidos de la venta <br><br>


          
          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <select class="form-control" name="nombreNew" id="editar">
                <option value="">Seleccionar cliente</option>
                <?php 
                  require_once("conexion.php");
                  conectar();
                  global $conn;
                  $query = "SELECT * FROM cliente";
                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  $result = $stmt->fetchALL();

                  for ($i=0; $i < count($result); $i++) { 
                    echo "<option value='" . $result[$i]["id"] . "'>" . $result[$i]["nombre"] . "</option>";
                  }


                ?>
              </select>

            </div>

          </div>


          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="conceptoNew" class="form-control" placeholder="Ingresar concepto" required>

            </div>

          </div>


          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-receipt"></i></span>

              <input type="text" name="importeNew" class="form-control" placeholder="Ingresar importe" required>

            </div>

          </div>


          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-receipt"></i></span>

              <input type="text" name="numFacturaNew" class="form-control" placeholder="Ingresar n&uacute;mero de factura" required>

            </div>

          </div>


          Fecha de venta

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>

              <input type="date" name="fechaNew" class="form-control" placeholder="Ingresar fecha de venta" required>

            </div>

          </div>


          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>

              <input type="number" name="ivaNew" class="form-control" placeholder="Ingresar IVA (dejar en blanco si no fue registrada todav&iacute;a)">

            </div>

          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>

              <input type="number" name="sussNew" class="form-control" placeholder="Ingresar seguridad social (dejar en blanco si no fue registrada todav&iacute;a)">

            </div>

          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>

              <input type="number" name="gananciasNew" class="form-control" placeholder="Ingresar impuesto a las ganancias (dejar en blanco si no fue registrada todav&iacute;a)">

            </div>

          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>

              <input type="number" name="iibbNew" class="form-control" placeholder="Ingresar ingresos brutos (dejar en blanco si no fue registrada todav&iacute;a)">

            </div>

          </div>




          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>

              <input type="number" name="ingresoFinalNew" class="form-control" placeholder="Ingresar ingreso final (dejar en blanco si no fue registrada todav&iacute;a)">

            </div>

          </div>

          Fecha de pago (dejar en blanco si no fue pagada todav&iacute;a)

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>

              <input type="date" name="fechaPagoNew" class="form-control" placeholder="Ingresar fecha de pago (dejar en blanco si no fue pagada todav&iacute;a)">

            </div>

          </div>






        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

        <button type="submit" name="editar" class="btn btn-primary">Guardar venta</button>

      </div>

      </form>

    </div>

  </div>
</div>


<div id="modalConsultaVenta" class="modal fade" role="dialog">

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

              <select class="form-control" name="nombreConsulta" id="consultar">
                <option value="">Todos los clientes</option>
                <?php 
                  require_once("conexion.php");
                  conectar();
                  global $conn;
                  $query = "SELECT * FROM cliente";
                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  $result = $stmt->fetchALL();

                  for ($i=0; $i < count($result); $i++) { 
                    echo "<option value='" . $result[$i]["id"] . "'>" . $result[$i]["nombre"] . "</option>";
                  }


                ?>
              </select>

            </div>

          </div>

          <br>

          
          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-file-alt"></i></span>

               <select class="form-control" name="queVentas" form="consultar">
                <option value="todas">Todas las ventas</option>
                <option value="noPagas">S&oacute;lo ventas no pagadas</option>
                <option value="pagas">S&oacute;lo ventas pagadas</option>
              </select> 

            </div>

          </div>


          Desde

          <br><br>

          <div class="form-group">

            <div class="input-group">

              
              <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>

              <input type="date" name="desde" class="form-control" placeholder="Desde">

            </div>

          </div>

          Hasta

          <br><br>

          <div class="form-group">

            <div class="input-group">


              <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>

              <input type="date" name="hasta" class="form-control" placeholder="Hasta">

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


<div id="modalAgregarPago" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post" id="agregarPago">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Agregar pago</h4>

      </div>

      <div class="modal-body">
        
        <div class="box-body">

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <select class="form-control" name="nombre" id="agregarPago">
                <option value="">Seleccionar cliente</option>
                <?php 
                  require_once("conexion.php");
                  conectar();
                  global $conn;
                  $query = "SELECT * FROM cliente";
                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  $result = $stmt->fetchALL();

                  for ($i=0; $i < count($result); $i++) { 
                    echo "<option value='" . $result[$i]["id"] . "'>" . $result[$i]["nombre"] . "</option>";
                  }


                ?>
              </select>

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

              <span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>

              <input type="number" name="iva" class="form-control" placeholder="Ingresar IVA" required>

            </div>

            

          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>

              <input type="number" name="suss" class="form-control" placeholder="Ingresar seguridad social" required>

            </div>

            
          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>

              <input type="number" name="ganancias" class="form-control" placeholder="Ingresar ganancias" required>

            </div>

            
          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>

              <input type="number" name="iibb" class="form-control" placeholder="Ingresar ingresos brutos" required>

            </div>

            
          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>

              <input type="number" name="ingresoFinal" class="form-control" placeholder="Ingresar ingreso final" required>

            </div>

            
          </div>


          Fecha de pago

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>

              <input type="date" name="fechaPago" class="form-control" placeholder="Ingresar fecha de pago" required>

            </div>

            
          </div>

        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

        <button type="submit" name="agregarPago" class="btn btn-primary">Agregar pago</button>

      </div>

      </form>

    </div>

  </div>
</div>
<script>

  $(document).ready(function(){

    $(".dt-buttons").children().addClass("btn btn-success");

    $("#lista").children().removeClass();
    $("#compras").addClass("active");


  });
  
  

</script>



<div class="content-wrapper">
    <section class="content-header">
      <h1>
        Administrar compras
  
      </h1>
      <ol class="breadcrumb">
        <li><a href="inicio"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
        <li class="active">Administrar compras</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">

        <div class="box-header with-border">


          <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarCompra">
            
            Agregar compra

          </button>

        </div>
      </div>



      <div class="box">

        <div class="box-header with-border">

          <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarPago">
            
            Agregar pago

          </button>


        </div>


        
        
      </div>

      <div class="box">

        <div class="box-header with-border">

          <button class="btn btn-primary" data-toggle="modal" data-target="#modalConsultaCompra">
            
            Consultar compra

          </button>

        <div class="box-body">

          
            

          


          <?php 

            $servername = "localhost";
            $username = "root";
            $password = "root";
            $dsn_Options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

            try {

                $conn = new PDO("mysql:host=$servername;dbname=sistemapos", $username, $password, $dsn_Options);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


              if (isset($_POST["agregar"])) {

                  $query = "INSERT INTO compra (`nombre de fantasia`, `razon social`, `cuit cuil`, `numero de factura`, fecha, `tipo de factura`, `importe total`, `pago total`) VALUES (:nombre, :razSoc, :cuitCuil, :numFactura, :fecha, :tipoFactura, :importeTotal, :pagoTotal)";
                  $stmt = $conn->prepare($query);
                  
                  $stmt->bindParam(":nombre", $_POST["nombre"]);
                  $stmt->bindParam(":razSoc", $_POST["razSoc"]);
                  $stmt->bindParam(":cuitCuil", $_POST["cuitCuil"]);
                  $stmt->bindParam(":numFactura", $_POST["numFactura"]);
                  $stmt->bindParam(":fecha", $_POST["fecha"]);
                  $stmt->bindParam(":tipoFactura", $_POST["tipoFactura"]);
                  $stmt->bindParam(":importeTotal", $_POST["importeTotal"]);
                  $stmt->bindParam(":pagoTotal", $_POST["pagoTotal"]);
                  

                  $stmt->execute(); 

                  echo "¡Compra agregada exitosamente!";

               }


               if (isset($_POST["consultar"])) {

                  $razSoc = $_POST["razSoc"];
                  $queCompras = $_POST["queCompras"];
                  $queFactura = $_POST["queFactura"];
                  $desde = $_POST["desde"];
                  $hasta = $_POST["hasta"];
                  $importes = $_POST["importes"];
                  $queryVenta = "";

                  //query a venta


                  if ($desde == '' && $hasta == '') {

                      $queryCompra = "SELECT * FROM compra";

                  } else if ($desde == '') {

                      $queryCompra = "SELECT * FROM compra WHERE fecha <= '" . $hasta . "'";

                  } else if ($hasta == '') {

                      $queryCompra = "SELECT * FROM compra WHERE fecha >= '" . $desde . "'";

                  } else {

                      $queryCompra = "SELECT * FROM compra WHERE fecha BETWEEN '" . $desde . "' AND '" . $hasta . "'";

                  }

                  if ($razSoc !== '') {


                    if (strlen($queryCompra) == 20) {

                      $queryCompra .= " WHERE `razon social` = '" . $razSoc . "'";

                    } else {

                      $queryCompra .= " AND `razon social` = '" . $razSoc . "'";

                    }

                  }

                  $stmt1 = $conn->prepare($queryCompra);
                  $stmt1->execute();
                  $resultCompra = $stmt1->fetchAll();


                  if ($queCompras == "pagas") {

                    for ($i=0; $i < count($resultCompra) ; $i++) { 
                      
                      if ($resultCompra[$i]["importe total"] > $resultCompra[$i]["pago total"]){

                        $resultCompra[$i] = NULL;

                      }

                    }

                  } else if ($queCompras == "noPagas") {

                    for ($i=0; $i < count($resultCompra) ; $i++) { 
                      
                      if ($resultCompra[$i]["importe total"] <= $resultCompra[$i]["pago total"]){

                        $resultCompra[$i] = NULL;

                      }

                    }

                  }

                  if ($queFactura == "A") {

                    for ($i=0; $i < count($resultCompra) ; $i++) { 
                      
                      if ($resultCompra[$i]["tipo de factura"] == "B" || $resultCompra[$i]["tipo de factura"] == "C"){

                        $resultCompra[$i] = NULL;

                      }

                    }

                  } else if ($queFactura == "C") {

                    for ($i=0; $i < count($resultCompra) ; $i++) { 
                      
                      if ($resultCompra[$i]["tipo de factura"] == "A" || $resultCompra[$i]["tipo de factura"] == "B"){

                        $resultCompra[$i] = NULL;

                      }

                    }

                  }

                 

                 $consulta = '<table class="table table-bordered table-striped tabla">
                  <thead>
                  <tr>
                    <th>Nombre de fantasia</th>
                    <th>Raz&oacute;n social</th>
                    <th>CUIT/CUIL</th>
                    <th>N&uacute;mero de factura</th>
                    <th>Fecha de compra</th>
                    <th>Tipo de factura</th>
                    <th>Importe total</th>
                    <th>Pago</th>
                  </tr>
                </thead>
                  <tbody>';

                 $sumaImportes = 0;
                 $sumaIngresosFinales = 0;

                 for ($i=0; $i < count($resultCompra); $i++) { 
                   if (!is_null($resultCompra[$i])) {
                    $consulta .=  "<tr><td>" . $resultCompra[$i]["nombre de fantasia"] . "</td><td>" . $resultCompra[$i]["razon social"] . "</td><td>" . $resultCompra[$i]["cuit cuil"] . "</td><td>" . $resultCompra[$i]["numero de factura"] . "</td><td>" . $resultCompra[$i]["fecha"] . "</td><td>" . $resultCompra[$i]["tipo de factura"] . "</td><td>" . $resultCompra[$i]["importe total"] . "</td><td>" . $resultCompra[$i]["pago total"] . "</td></tr>";
                    $sumaImportes += $resultCompra[$i]["importe total"];
                   }      
                   
                 }

                 // if ($importes) {
                 //  $consulta .= $sumaImportes . "<br>";
                 // }

                 echo $consulta . '</tbody></table>';
                 
               }


               if (isset($_POST["agregarPago"])) {

                  $razSoc = $_POST["razSoc"];
                  $numFactura = $_POST["numFactura"];
                  $medioPago = $_POST["medioPago"];
                  $fechaPago = $_POST["fechaPago"];
                  $pago = $_POST["pago"];

                  $queryCompraId = "SELECT id FROM compra WHERE `razon social` ='" . $razSoc . "' AND `numero de factura` = '" . $numFactura . "'";
                  $stmt = $conn->prepare($queryCompraId);
                  $stmt->execute();
                  $result = $stmt->fetchALL();

                  $compra_id = $result[0][0];
                    
                  $query = "INSERT INTO pago (compra_id, `medio de pago`, `fecha de pago`, pago) VALUES (:compra_id, :medioPago, :fechaPago, :pago)";

                  $stmt = $conn->prepare($query);
                  $stmt->bindParam(":compra_id", $compra_id);
                  $stmt->bindParam(":medioPago", $medioPago);
                  $stmt->bindParam(":fechaPago", $fechaPago);
                  $stmt->bindParam(":pago", $pago);

                  $stmt->execute(); 

                  $queryActualizar = "UPDATE compra SET `pago total` = `pago total` + " . $pago . " WHERE id = " . $compra_id;

                  $stmt1 = $conn->prepare($queryActualizar);
                  $stmt1->execute();


                  echo "¡Pago agregado exitosamente!";


                  
                 
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

<div id="modalAgregarCompra" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Agregar compra</h4>

      </div>

      <div class="modal-body">
        
        <div class="box-body">

          
          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="nombre" class="form-control" placeholder="Ingresar nombre de fantas&iacute;a del proveedor">

            </div>

          </div>


          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="razSoc" class="form-control" placeholder="Ingresar raz&oacute;n social" required>

            </div>

          </div>


          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-receipt"></i></span>

              <input type="text" name="cuitCuil" class="form-control" placeholder="Ingresar CUIT/CUIL" required>

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

              <span class="input-group-addon"><i class="fa fa-receipt"></i></span>

              <input type="text" name="tipoFactura" class="form-control" placeholder="Ingresar tipo de factura" required>

            </div>

          </div>


          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>

              <input type="date" name="fecha" class="form-control" placeholder="Ingresar fecha de compra" required>

            </div>

          </div>


          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>

              <input type="number" name="importeTotal" class="form-control" placeholder="Ingresar importe total" required>

            </div>

          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>

              <input type="number" name="pagoTotal" class="form-control" placeholder="Ingresar pago (dejalo en 0 si todav&iacute;a no fue efectuado)" required>

            </div>

          </div>









        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

        <button type="submit" name="agregar" class="btn btn-primary">Guardar compra</button>

      </div>

      </form>

    </div>

  </div>
</div>




<div id="modalConsultaCompra" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post" id="consultar">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Consultar datos de la venta</h4>

      </div>

      <div class="modal-body">
        
        <div class="box-body">
          
          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="razSoc" class="form-control" placeholder="Ingresar raz&oacute;n social o dejar en blanco si desea consultar mediante otros par&aacute;metros">

            </div>

          </div>

          <br>

          
          <div class="form-group">

            <div class="input-group">

              

               <select name="queCompras" form="consultar">
                <option value="todas">Todas las compras</option>
                <option value="noPagas">S&oacute;lo compras no pagadas</option>
                <option value="pagas">S&oacute;lo compras pagadas</option>
              </select> 

            </div>

          </div>

          <div class="form-group">

            <div class="input-group">

              

               <select name="queFactura" form="consultar">
                <option value="todas">Todas los tipo de factura</option>
                <option value="A">S&oacute;lo facturas A</option>
                <option value="C">S&oacute;lo facturas C</option>
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

          <div class="form-group">

            <div class="input-group">

              

              Incluir suma de importes totales&nbsp; 

              <input type="checkbox" name="importes">

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

      <form role="form" method="post">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Agregar pago</h4>

      </div>

      <div class="modal-body">
        
        <div class="box-body">

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="razSoc" class="form-control" placeholder="Ingresar raz&oacute;n social" required>

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

              <input type="number" name="pago" class="form-control" placeholder="Ingresar monto del pago" required>

            </div>

            

          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-money-check"></i></span>

              <input type="text" name="medioPago" class="form-control" placeholder="Ingresar medio de pago" required>

            </div>

            
          </div>

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
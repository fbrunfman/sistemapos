<script>

  $(document).ready(function(){

    $(".dt-buttons").children().addClass("btn btn-success");

    $("#lista").children().removeClass();
    $("#compras").addClass("active");

    $("#cng").hide();

    $("#check").change(function(){

      if(this.checked){
        $("#cng").fadeIn();
      } else {
        $("#cng").fadeOut();
      }

    })
    

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


          <button class="btn btn-primary" data-toggle="modal" data-target="#modalEditarCompra">
            
            Editar compra

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


                function formatearFecha($fecha) {
                 $year =  substr($fecha, 0, 4);
                 $mes = substr($fecha, 5, 2);
                 $dia = substr($fecha, 8, 2);

                 return $dia . "-" . $mes . "-" . $year;
                 
                }


              if (isset($_POST["agregar"])) {

                  // $queryId = "SELECT * FROM proveedor WHERE id= " . $_POST["id"];
                  // $stmt1 = $conn->prepare($queryId);
                  // $stmt1->execute();
                  // $result = $stmt1->fetchALL();
                  // $proveedor_id = $result[0][0];

                  $queryProveedor = "SELECT `tipo de factura` FROM proveedor WHERE id = " . $_POST["id"];
                  $stmtP = $conn->prepare($queryProveedor);
                  $stmtP->execute();
                  $result = $stmtP->fetchALL();

                  $tipoFactura = $result[0][0];

                  $importeNeto = $_POST["importeTotal"] - $_POST["cng"];
                  $iva = 0;

                  if ($tipoFactura == 'A') {

                    $iva = $importeNeto * 0.21;
                    $importeNeto *= 0.79;
                    
                  }


                  $query = "INSERT INTO compra (proveedor_id, `numero de factura`, fecha, `importe total`, cng, iva, `importe neto`) VALUES (:proveedor_id, :numFactura, :fecha, :importeTotal, :cng, :iva, :importeNeto)";
                  $stmt = $conn->prepare($query);
                     
                  $stmt->bindParam(":proveedor_id", $_POST["id"]);
                  $stmt->bindParam(":numFactura", $_POST["numFactura"]);
                  $stmt->bindParam(":fecha", $_POST["fecha"]);
                  $stmt->bindParam(":importeTotal", $_POST["importeTotal"]);
                  $stmt->bindParam(":cng", $_POST["cng"]);
                  $stmt->bindParam(":importeNeto", $importeNeto);
                  $stmt->bindParam(":iva", $iva);

                  

                  $stmt->execute(); 

                  echo "<script>swal('¡Compra agregada exitosamente!')</script>";

               }


               if (isset($_POST["editar"])) {

                  $id = $_POST["id"];
                  $numFactura = $_POST["numFactura"];
                  $idNew = $_POST["idNew"];
                  $numFacturaNew = $_POST["numFacturaNew"];
                  $fechaNew = $_POST["fechaNew"];
                  $importeTotalNew = $_POST["importeTotalNew"];


                  $queryEditar = "UPDATE compra SET proveedor_id = " . $idNew . ", `numero de factura` = '" . $numFacturaNew . "', fecha = '" . $fechaNew . "', `importe total` = " . $importeTotalNew . " WHERE proveedor_id = " . $id . " AND `numero de factura` = '" . $numFactura . "'";
                  $stmt = $conn->prepare($queryEditar);
                  $stmt->execute();

                  echo "<script>swal('¡Edición realizada exitosamente!')</script>";

               }


               if (isset($_POST["consultar"])) {

                  $proveedor_id = $_POST["razSoc"];
                  $queCompras = $_POST["queCompras"];
                  $queFactura = $_POST["queFactura"];
                  $desde = $_POST["desde"];
                  $hasta = $_POST["hasta"];
                  $importes = $_POST["importes"];


                  // por fechas

                  if ($desde == '' && $hasta == '') {

                      $queryCompra = "SELECT * FROM compra";

                  } else if ($desde == '') {

                      $queryCompra = "SELECT * FROM compra WHERE fecha <= '" . $hasta . "'";

                  } else if ($hasta == '') {

                      $queryCompra = "SELECT * FROM compra WHERE fecha >= '" . $desde . "'";

                  } else {

                      $queryCompra = "SELECT * FROM compra WHERE fecha BETWEEN '" . $desde . "' AND '" . $hasta . "'";

                  }

                  // por proveedor


                  if ($proveedor_id !== '') {


                    if (strlen($queryCompra) == 20) {

                      $queryCompra .= " WHERE proveedor_id = '" . $proveedor_id . "'";

                    } else {

                      $queryCompra .= " AND proveedor_id = '" . $proveedor_id . "'";

                    }

                  }

                  $stmt1 = $conn->prepare($queryCompra);
                  $stmt1->execute();
                  $resultCompra = $stmt1->fetchAll();

                  // por pagas o no pagas

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

              

                  // por tipo de factura


                  for ($j=0; $j < count($resultCompra); $j++) { 
                    
                      $queryA = "SELECT `tipo de factura` FROM proveedor WHERE id = " . $resultCompra[$j]["proveedor_id"];

                      $stmt = $conn->prepare($queryA);
                      $stmt->execute();
                      $result = $stmt->fetchALL();
                      $tipoFactura = $result[0][0];


                      if ($queFactura == 'A' && $tipoFactura != 'A') {
                        $resultCompra[$j] = NULL;
                      } else if ($queFactura == 'C' && $tipoFactura != 'C') {
                        $resultCompra[$j] = NULL;
                      }
                    
                  }


                 

                 $consulta = '<table class="table table-bordered table-striped tabla">
                  <thead>
                  <tr>
                    <th>ID de compra</th> 
                    <th>ID de proveedor/Raz&oacute;n social</th>
                    <th>N&uacute;mero de factura</th>
                    <th>Fecha de compra</th>
                    <th>Conceptos no gravados</th>
                    <th>IVA</th>
                    <th>Importe neto</th>
                    <th>Importe total</th>
                    <th>Pago</th>
                  </tr>
                </thead>
                  <tbody>';


                 for ($i=0; $i < count($resultCompra); $i++) { 

                  if (!is_null($resultCompra[$i])) {  


                    $query = "SELECT `razon social` FROM proveedor WHERE id = " . $resultCompra[$i]["proveedor_id"];
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $result = $stmt->fetchALL();
                    $razSoc = $result[0][0]; 


                    $consulta .=  "<tr><td>" . $resultCompra[$i]["id"] . "</td><td>" .  $resultCompra[$i]["proveedor_id"] . " / " . $razSoc . "</td><td>" . $resultCompra[$i]["numero de factura"] . "</td><td>" . formatearFecha($resultCompra[$i]["fecha"]) .  "</td><td>" . $resultCompra[$i]["cng"] . "</td><td>" . $resultCompra[$i]["iva"] . "</td><td>" . $resultCompra[$i]["importe neto"] . "</td><td>" . $resultCompra[$i]["importe total"] . "</td><td>" . $resultCompra[$i]["pago total"] . "</td></tr>";
                   }      
                   
                 }

                 echo $consulta . '</tbody></table>';
                 
               }


               if (isset($_POST["agregarPago"])) {

                  $proveedor_id = $_POST["razSoc"];
                  $numFactura = $_POST["numFactura"];
                  $medioPago = $_POST["medioPago"];
                  $fechaPago = $_POST["fechaPago"];
                  $pago = $_POST["pago"];

                  $queryCompraId = "SELECT id FROM compra WHERE proveedor_id ='" . $proveedor_id . "' AND `numero de factura` = '" . $numFactura . "'";
                  $stmt1 = $conn->prepare($queryCompraId);
                  $stmt1->execute();
                  $result1 = $stmt1->fetchALL();

                  $compra_id = $result1[0][0];
                    
                  $queryPago = "INSERT INTO pago (compra_id, `medio de pago`, `fecha de pago`, pago) VALUES (:compra_id, :medioPago, :fechaPago, :pago)";

                  $stmt = $conn->prepare($queryPago);
                  $stmt->bindParam(":compra_id", $compra_id);
                  $stmt->bindParam(":medioPago", $medioPago);
                  $stmt->bindParam(":fechaPago", $fechaPago);
                  $stmt->bindParam(":pago", $pago);

                  $stmt->execute(); 

                  $queryActualizar = "UPDATE compra SET `pago total` = `pago total` + " . $pago . " WHERE id = " . $compra_id;

                  $stmt1 = $conn->prepare($queryActualizar);
                  $stmt1->execute();


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


      <div class="box">

        <div class="box-header with-border">

          <button class="btn btn-primary" data-toggle="modal" data-target="#modalVerPagos">
            
            Ver pagos

          </button>

          <div class="box-body">
            

            <?php 


              if (isset($_POST["verPagos"])) {

                require_once('conexion.php');
  
                conectar();
  
                global $conn;
  
  
                $query = "SELECT * FROM pago WHERE compra_id = " . $_POST["id"];
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->fetchALL();
  
  
                $consulta = '<table class="table table-bordered table-striped tabla">
                              <thead>
                                <th>Fecha de pago</th>
                                <th>Medio de pago</th>
                                <th>Pago</th>
                              </thead>
                              <tbody>';
  
                for ($i=0; $i < count($result); $i++) { 
                  $consulta .= '<tr><td>' . date('d-m-Y', strtotime($result[$i][3])) . '</td><td>' . $result[$i][2] . '</td><td>' . $result[$i][4] . '</td></tr>';
                }
  
                $consulta .= '</tbody></table>';
  
  
  
                echo $consulta;


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

      <form role="form" method="post" id="agregar">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Agregar compra</h4>

      </div>

      <div class="modal-body">
        
        <div class="box-body">

          
         


          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <!-- <input type="text" name="razSoc" class="form-control" placeholder="Ingresar raz&oacute;n social" required> -->

              <select class="form-control" name="id" id="agregar">
                <option value="">Ingresar raz&oacute;n social</option>
                <?php 
                  require_once("conexion.php");
                  conectar();
                  global $conn;
                  $query = "SELECT * FROM proveedor";
                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  $result = $stmt->fetchALL();

                  for ($i=0; $i < count($result); $i++) { 
                    echo "<option value='" . $result[$i]["id"] . "'>" . $result[$i]["razon social"] . "</option>";
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


          

          Fecha de compra


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

          Conceptos no gravados

          <input type="checkbox" name="Conceptos no gravados" id="check">

          <br><br>


          <div id="cng">
            
            <input name="cng" type="number" value="0">

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

<div id="modalEditarCompra" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Editar compra</h4>

      </div>

      <div class="modal-body">
        
        <div class="box-body">

          
          Ingres&aacute; los datos de la compra que quieras editar <br><br>

          <div class="form-group">
            <div class="input-group">

          <span class="input-group-addon"><i class="fa fa-user"></i></span>


          <select class="form-control" name="id" id="agregar">
                <option value="">Ingresar raz&oacute;n social</option>
                <?php 
                  require_once("conexion.php");
                  conectar();
                  global $conn;
                  $query = "SELECT * FROM proveedor";
                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  $result = $stmt->fetchALL();

                  for ($i=0; $i < count($result); $i++) { 
                    echo "<option value='" . $result[$i]["id"] . "'>" . $result[$i]["razon social"] . "</option>";
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


          Ingres&aacute; todos los datos corregidos de la compra <br><br>


          

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-receipt"></i></span>

              <select class="form-control" name="idNew" id="agregar">
                <option value="">Ingresar raz&oacute;n social</option>
                <?php 
                  require_once("conexion.php");
                  conectar();
                  global $conn;
                  $query = "SELECT * FROM proveedor";
                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  $result = $stmt->fetchALL();

                  for ($i=0; $i < count($result); $i++) { 
                    echo "<option value='" . $result[$i]["id"] . "'>" . $result[$i]["razon social"] . "</option>";
                  }


                ?>

              </select>

            </div>

          </div>


          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-receipt"></i></span>

              <input type="text" name="numFacturaNew" class="form-control" placeholder="Ingresar n&uacute;mero de factura" required>

            </div>

          </div>


          Fecha de compra


          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>

              <input type="date" name="fechaNew" class="form-control" required>

            </div>

          </div>


          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>

              <input type="number" name="importeTotalNew" class="form-control" placeholder="Ingresar importe total" required>

            </div>

          </div>






        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

        <button type="submit" name="editar" class="btn btn-primary">Guardar compra</button>

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

        <h4 class="modal-title">Consultar datos de la compra</h4>

      </div>

      <div class="modal-body">
        
        <div class="box-body">
          
          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <!-- <input type="text" name="razSoc" class="form-control" placeholder="Ingresar raz&oacute;n social o dejar en blanco si desea consultar mediante otros par&aacute;metros"> -->

              <select class="form-control" name="razSoc" id="consultar">
                <option value="">Ingresar raz&oacute;n social</option>
                <?php 
                  require_once("conexion.php");
                  conectar();
                  global $conn;
                  $query = "SELECT * FROM proveedor";
                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  $result = $stmt->fetchALL();

                  for ($i=0; $i < count($result); $i++) { 
                    echo "<option value='" . $result[$i]["id"] . "'>" . $result[$i]["razon social"] . "</option>";
                  }


                ?>

              </select>



            </div>

          </div>

          <br>

          
          <div class="form-group">

            <div class="input-group">

              

               <select class="form-control" name="queCompras" form="consultar">
                <option value="todas">Todas las compras</option>
                <option value="noPagas">S&oacute;lo compras no pagadas</option>
                <option value="pagas">S&oacute;lo compras pagadas</option>
              </select> 

            </div>

          </div>

          <div class="form-group">

            <div class="input-group">

              

               <select class="form-control" name="queFactura" form="consultar">
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



<div id="modalVerPagos" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post" id="consultar">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Consultar datos de la compra</h4>

      </div>

      <div class="modal-body">
        
        <div class="box-body">


          
          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="number" name="id" class="form-control" placeholder="Ingresar id de compra" required>

            </div>

          </div>

          

        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

        <button type="submit" name="verPagos" class="btn btn-primary">Ver pagos</button>

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

              <!-- <input type="text" name="razSoc" class="form-control" placeholder="Ingresar raz&oacute;n social" required> -->

              <select class="form-control" name="razSoc" id="agregarPago">
                <option value="">Ingresar raz&oacute;n social</option>
                <?php 
                  require_once("conexion.php");
                  conectar();
                  global $conn;
                  $query = "SELECT * FROM proveedor";
                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  $result = $stmt->fetchALL();

                  for ($i=0; $i < count($result); $i++) { 
                    echo "<option value='" . $result[$i]["id"] . "'>" . $result[$i]["razon social"] . "</option>";
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

              <input type="number" name="pago" class="form-control" placeholder="Ingresar monto del pago" required>

            </div>

            

          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-money-check"></i></span>

              <!-- <input type="text" name="medioPago" class="form-control" placeholder="Ingresar medio de pago" required> -->

              <select class="form-control" name="medioPago" id="agregarPago">
                <option value="Transferencia">Transferencia</option>
                <option value="Efectivo">Efectivo</option>
                <option value="Cheque">Cheque</option>
                <option value="Tarjeta de cr&eacute;dito corporativa">Tarjeta de cr&eacute;dito corporativa</option>
                <option value="Tarjeta de d&eacute;bito corporativa">Tarjeta de d&eacute;bito corporativa></option>
              </select>

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
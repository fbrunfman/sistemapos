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

          <br><br>

          <button class="btn btn-primary" data-toggle="modal" data-target="#modalConsultaVenta">
            
            Consultar venta

          </button>

          <br><br>

          <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarPago">
            
            Agregar pago

          </button>


        </div>


        <div class="box-body">

          <table class="table table-bordered table-striped tabla table-responsive">
            

          </table>


          <?php 

            $servername = "localhost";
            $username = "root";
            $password = "root";
            $dsn_Options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

            try {

                $conn = new PDO("mysql:host=$servername;dbname=sistemapos", $username, $password, $dsn_Options);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


              if (isset($_POST["agregar"])) {

                  $queryClienteId = "SELECT id from cliente WHERE nombre='" . $_POST["nombre"] . "'";
                  $stmt = $conn->prepare($queryClienteId);
                  $stmt->execute();
                  $result = $stmt->fetchALL();

                  $cliente_id = $result[0][0];

                  

                  $query = "INSERT INTO venta (cliente_id, concepto, importe, `numero de factura`, `fecha de venta`) VALUES (:cliente_id, :concepto, :importe, :numFactura, :fechaVenta)";
                  $stmt = $conn->prepare($query);
                  $stmt->bindParam(":cliente_id", $cliente_id);
                  $stmt->bindParam(":concepto", $_POST["concepto"]);
                  $stmt->bindParam(":importe", $_POST["importe"]);
                  $stmt->bindParam(":numFactura", $_POST["numFactura"]);
                  $stmt->bindParam(":fechaVenta", $_POST["fechaVenta"]);

                  $stmt->execute(); 

                  echo "¡Venta agregada exitosamente!";

               }


               if (isset($_POST["consultar"])) {

                  $query = "SELECT * FROM cliente WHERE nombre='" . $_POST["nombreConsulta"] . "'";

                  $stmt = $conn->prepare($query);
                  $stmt->execute(); 

                  $result = $stmt->fetchALL();

                  echo "Nombre: " . $result[0][1] . "<br>" .
                  "CUIT: " .  $result[0][2];
                 
               }


               if (isset($_POST["agregarPago"])) {

                  $queryClienteId = "SELECT id from cliente WHERE nombre='" . $_POST["nombre"] . "'";
                  $stmt = $conn->prepare($queryClienteId);
                  $stmt->execute();
                  $result = $stmt->fetchALL();

                  $cliente_id = $result[0][0];


                  $query = "UPDATE venta SET iva = " . $_POST["iva"] . ", suss = " . $_POST["suss"] . ", ganancias = " . $_POST["ganancias"] . ", iibb = " . $_POST["iibb"] . ", `ingreso final` = " . $_POST["ingresoFinal"] . ", `fecha de pago`= '" . $_POST["fechaPago"] . "' WHERE cliente_id = " . $cliente_id . " AND `numero de factura` ='" . $_POST["numFactura"] . "'";

                  $stmt = $conn->prepare($query);
                  $stmt->execute(); 

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

<!-- Modal -->

<div id="modalAgregarVenta" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Agregar venta</h4>

      </div>

      <div class="modal-body">
        
        <div class="box-body">
          
          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="nombre" class="form-control" placeholder="Ingresar nombre del cliente" required>

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




<div id="modalConsultaVenta" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Consultar datos de la venta</h4>

      </div>

      <div class="modal-body">
        
        <div class="box-body">
          
          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="nombreConsulta" class="form-control" placeholder="Ingresar nombre" required>

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

              <input type="text" name="nombre" class="form-control" placeholder="Ingresar nombre del cliente" required>

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
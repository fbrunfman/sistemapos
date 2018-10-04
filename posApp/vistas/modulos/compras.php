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

          <br><br>

          <button class="btn btn-primary" data-toggle="modal" data-target="#modalConsultaCompra">
            
            Consultar compra

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

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="cuitCuil" class="form-control" placeholder="Ingresar CUIT/CUIL" required>

            </div>

          </div>


          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="numFactura" class="form-control" placeholder="Ingresar n&uacute;mero de factura" required>

            </div>

          </div>


          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="date" name="fecha" class="form-control" placeholder="Ingresar fecha de compra" required>

            </div>

          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="tipoFactura" class="form-control" placeholder="Ingresar tipo de factura" required>

            </div>

          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="number" name="importeTotal" class="form-control" placeholder="Ingresar importe total" required>

            </div>

          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="number" name="pagoTotal" class="form-control" placeholder="Ingresar pago" value=0 required>

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

      <form role="form" method="post">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Consultar datos de la compra</h4>

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

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="numFactura" class="form-control" placeholder="Ingresar n&uacute;mero de factura" required>

            </div>

            

          </div>

          
          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="number" name="iva" class="form-control" placeholder="Ingresar IVA" required>

            </div>

            

          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-file-alt"></i></span>

              <input type="number" name="suss" class="form-control" placeholder="Ingresar seguridad social" required>

            </div>

            
          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-file-alt"></i></span>

              <input type="number" name="ganancias" class="form-control" placeholder="Ingresar ganancias" required>

            </div>

            
          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-file-alt"></i></span>

              <input type="number" name="iibb" class="form-control" placeholder="Ingresar ingresos brutos" required>

            </div>

            
          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-file-alt"></i></span>

              <input type="number" name="ingresoFinal" class="form-control" placeholder="Ingresar ingreso final" required>

            </div>

            
          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-file-alt"></i></span>

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
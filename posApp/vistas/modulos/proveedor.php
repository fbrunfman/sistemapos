<script>
  
  $(document).ready(function(){

    $(".dt-buttons").children().hide();
  
    $("#lista").children().removeClass();
    $("#proveedores").addClass("active");


  });

</script>

<div class="content-wrapper">
    <section class="content-header">
      <h1>
        Administrar proveedores
  
      </h1>
      <ol class="breadcrumb">
        <li><a href="inicio"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
        <li class="active">Administrar proveedores</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">

        <div class="box-header with-border">


          <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarProveedor">
            
            Agregar proveedor

          </button>

        </div>
      </div>

      <div class="box">

        <div class="box-header with-border">

          <button class="btn btn-primary" data-toggle="modal" data-target="#modalConsultaProveedor">
            
            Consultar datos de proveedor

          </button>


        </div>
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

                  $query = "INSERT INTO proveedor (`nombre de fantasia`, `razon social`, `cuit cuil`, `tipo de factura`) VALUES (:nombreFantasia, :razonSocial, :cuitCuil, :tipoFactura)";
                  $stmt = $conn->prepare($query);
                  $stmt->bindParam(":nombreFantasia", $_POST["nombreFantasia"]);
                  $stmt->bindParam(":razonSocial", $_POST["razSoc"]);
                  $stmt->bindParam(":cuitCuil", $_POST["cuitCuil"]);
                  $stmt->bindParam(":tipoFactura", $_POST["tipoFactura"]);
                  $stmt->execute(); 

                  echo "<script>swal('¡Proveedor agregado exitosamente!')</script>";
                  

               }


               if (isset($_POST["consultar"])) {



                  $query = "SELECT * FROM proveedor";

                  if ($_POST["razConsulta"] !== "") {
                    $query .=  " WHERE id= " . $_POST["razConsulta"];
                  }

                  $stmt = $conn->prepare($query);
                  $stmt->execute(); 

                  $result = $stmt->fetchALL();

                  $consulta = '<table class="table table-bordered table-striped tabla table-responsive">
                    <thead>
                    <tr> 
                    <th>Nombre de fantasia</th>
                    <th>Raz&oacute;n social</th>
                    <th>CUIT/CUIL</th>
                    <th>Tipo de factura</th>
                    </tr>
                    </thead>
                    <tbody>';

                  for ($i=0; $i < count($result) ; $i++) { 
                    $consulta .= "<tr><td>" . $result[$i][1] . "</td><td>" . $result[$i][2] . "</td><td>" . $result[$i][3] . "</td><td>" . $result[$i][4] . "</td></tr>";
                  }

                  echo $consulta . '</tbody></table>';
                 
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

<!-- Modal -->

<div id="modalAgregarProveedor" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Agregar proveedor</h4>

      </div>

      <div class="modal-body">
        
        <div class="box-body">
          
          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="nombreFantasia" class="form-control" placeholder="Ingresar nombre de fantasia" required>

            </div>

            

          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-file-alt"></i></span>

              <input type="text" name="razSoc" class="form-control" placeholder="Ingresar raz&oacute;n social" required>

            </div>

            

          </div>


          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="cuitCuil" class="form-control" placeholder="Ingresar CUIT/CUIL">

            </div>

            

          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="tipoFactura" class="form-control" placeholder="Ingresar tipo de factura">

            </div>

            

          </div>


        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

        <button type="submit" name="agregar" class="btn btn-primary">Guardar proveedor</button>

      </div>

      </form>

    </div>

  </div>
</div>




<div id="modalConsultaProveedor" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post" id="consulta">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Consultar datos de proveedor</h4>

      </div>

      <div class="modal-body">
        
        <div class="box-body">
          
          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <select class="form-control" name="razConsulta" id="consulta">
                <option value="">Todos los proveedores</option>

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



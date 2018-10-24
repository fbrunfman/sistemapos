<script>
  
  $(document).ready(function(){

    $(".dt-buttons").children().hide();
  
    $("#lista").children().removeClass();
    $("#clientes").addClass("active");


  });

</script>

<div class="content-wrapper">
    <section class="content-header">
      <h1>
        Administrar clientes
  
      </h1>
      <ol class="breadcrumb">
        <li><a href="inicio"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
        <li class="active">Administrar clientes</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">

        <div class="box-header with-border">


          <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarCliente">
            
            Agregar cliente

          </button>

        </div>
      </div>

      <div class="box">

        <div class="box-header with-border">

          <button class="btn btn-primary" data-toggle="modal" data-target="#modalConsultaCliente">
            
            Consultar datos de cliente

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

                  $query = "INSERT INTO cliente (nombre, cuit, email) VALUES (:nombre, :cuit, :email)";
                  $stmt = $conn->prepare($query);
                  $stmt->bindParam(":nombre", $_POST["nombre"]);
                  $stmt->bindParam(":cuit", $_POST["cuit"]);
                  $stmt->bindParam(":email", $_POST["email"]);
                  $stmt->execute(); 

                  echo "<script>swal('¡Cliente agregado exitosamente!')</script>";
                  

               }


               if (isset($_POST["consultar"])) {



                  $query = "SELECT * FROM cliente";

                  if ($_POST["nombreConsulta"] !== "") {
                    $query .=  " WHERE id='" . $_POST["nombreConsulta"] . "'";
                  }

                  $stmt = $conn->prepare($query);
                  $stmt->execute(); 

                  $result = $stmt->fetchALL();

                  $consulta = '<table class="table table-bordered table-striped tabla table-responsive">
                    <thead>
                    <tr> 
                    <th>Nombre</th>
                    <th>CUIT</th>
                    <th>E-mail</th>
                    </tr>
                    </thead>
                    <tbody>';

                  for ($i=0; $i < count($result) ; $i++) { 
                    $consulta .= "<tr><td>" . $result[$i][1] . "</td><td>" . $result[$i][2] . "</td><td>" . $result[$i][3] .  "</td></tr>";
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

<div id="modalAgregarCliente" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Agregar cliente</h4>

      </div>

      <div class="modal-body">
        
        <div class="box-body">
          
          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="nombre" class="form-control" placeholder="Ingresar nombre" required>

            </div>

            

          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-file-alt"></i></span>

              <input type="text" name="cuit" class="form-control" placeholder="Ingresar CUIT" required>

            </div>

            

          </div>


          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="email" class="form-control" placeholder="Ingresar e-mail">

            </div>

            

          </div>

        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

        <button type="submit" name="agregar" class="btn btn-primary">Guardar cliente</button>

      </div>

      </form>

    </div>

  </div>
</div>




<div id="modalConsultaCliente" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post" id="consulta">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Consultar datos de cliente</h4>

      </div>

      <div class="modal-body">
        
        <div class="box-body">
          
          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <!-- <input type="text" name="nombreConsulta" class="form-control" placeholder="Ingresar nombre (dejar en blanco si deseas ver a todos los clientes)"> -->

              <select class="form-control" name="nombreConsulta" id="consulta">
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



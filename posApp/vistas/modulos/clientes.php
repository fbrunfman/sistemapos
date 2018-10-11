<div class="content-wrapper">
    <section class="content-header">
      <h1>
        Administrar clientes
  
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


          <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarCliente">
            
            Agregar cliente

          </button>

          <br><br>

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

                  $query = "INSERT INTO cliente (nombre, cuit) VALUES (:nombre, :cuit)";
                  $stmt = $conn->prepare($query);
                  $stmt->bindParam(":nombre", $_POST["nombre"]);
                  $stmt->bindParam(":cuit", $_POST["cuit"]);
                  $stmt->execute(); 

                  echo "¡Cliente agregado exitosamente!<br>";
                  

               }


               if (isset($_POST["consultar"])) {

                  $query = "SELECT * FROM cliente WHERE nombre='" . $_POST["nombreConsulta"] . "'";

                  $stmt = $conn->prepare($query);
                  $stmt->execute(); 

                  $result = $stmt->fetchALL();

                  $consulta = '<table class="table table-bordered table-striped tabla table-responsive">
                    <thead>
                    <tr> 
                    <th>Nombre</th>
                    <th>CUIT</th>
                    </tr>
                    </thead>
                    <tbody>';

                  $consulta .= "<tr><td>" . $result[0][1] . "</td><br>" .
                  "<td>" .  $result[0][2] . "</td></tr>";

                  echo $consulta . '</tbody>

                  </table>';
                 
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

              <input type="text" name="nombre" class="form-control input-lg" placeholder="Ingresar nombre" required>

            </div>

            

          </div>

          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-file-alt"></i></span>

              <input type="text" name="cuit" class="form-control input-lg" placeholder="Ingresar CUIT" required>

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

      <form role="form" method="post">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Consultar datos de cliente</h4>

      </div>

      <div class="modal-body">
        
        <div class="box-body">
          
          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-user"></i></span>

              <input type="text" name="nombreConsulta" class="form-control input-lg" placeholder="Ingresar nombre" required>

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

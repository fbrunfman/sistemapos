<div class="content-wrapper">
<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        P&aacute;gina de inicio
        <small>Panel de control</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="inicio"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
        <li class="active">Tablero</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="box box-primary">
            <div class="box-header">
              <i class="ion ion-clipboard"></i>

              <h3 class="box-title">Lista de tareas</h3>

              <!-- <div class="box-tools pull-right">
                <ul class="pagination pagination-sm inline">
                  <li><a href="#">&laquo;</a></li>
                  <li><a href="#">1</a></li>
                  <li><a href="#">2</a></li>
                  <li><a href="#">3</a></li>
                  <li><a href="#">&raquo;</a></li>
                </ul>
              </div> -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
              
              <ul class="todo-list" data-widget="todo-list" id="list">
                <?php 

                  ob_start();

                  $servername = "localhost";
                  $username = "root";
                  $password = "root";
                  $dsn_Options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

                  try {

                      $conn = new PDO("mysql:host=$servername;dbname=sistemapos", $username, $password, $dsn_Options);
                      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                      $query = "SELECT * FROM todo";
                      $stmt = $conn->prepare($query);
                      $stmt ->execute();
                      $result = $stmt->fetchALL();

                      for ($i=0; $i < count($result); $i++) { 
                        echo '<li>
                          <!-- drag handle -->
                          <span class="handle">
                                <i class="fa fa-ellipsis-v"></i>
                                <i class="fa fa-ellipsis-v"></i>
                              </span>
                          <!-- checkbox -->
                          <input type="checkbox" value="">
                          <!-- todo text -->
                          <span class="text">' . $result[$i][1] . '</span>
                          <!-- Emphasis label -->
                          <!-- General tools such as edit or delete-->
                          <div class="tools">
                            <i class="fa fa-edit"></i>
                            <i class="fa fa-trash"></i>
                          </div>
                        </li>';



                      }

                      if(isset($_POST["agregar"])) {


                        
                          $query = "INSERT INTO todo (todo) VALUES ('" . $_POST["nota"] . "')";
                          $stmt = $conn->prepare($query);
                          $stmt->execute();



                          echo '<li>
                            <!-- drag handle -->
                            <span class="handle">
                                  <i class="fa fa-ellipsis-v"></i>
                                  <i class="fa fa-ellipsis-v"></i>
                                </span>
                            <!-- checkbox -->
                            <input type="checkbox" value="">
                            <!-- todo text -->
                            <span class="text">' . $_POST["nota"] . '</span>
                            <!-- Emphasis label -->
                            <!-- General tools such as edit or delete-->
                            <div class="tools">
                              <i class="fa fa-edit"></i>
                              <i class="fa fa-trash"></i>
                            </div>
                          </li>';



                          


                      }


                      
                      
                    }

                      catch(PDOException $e)
                        {
                        echo $message = $e->getMessage();
                        }

                      ?>

                  
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix no-border">
              <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#modalNota"><i class="fa fa-plus"></i> Add item</button>
            </div>
          </div>

    </section>
    <!-- /.content -->
</div>



<div id="modalNota" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <form role="form" method="post" id="form">

      <div class="modal-header" style="background: #3c8dbc; color: white;">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Agregar nota</h4>

      </div>

      <div class="modal-body">
        
        <div class="box-body">
          
          <div class="form-group">

            <div class="input-group">

              <span class="input-group-addon"><i class="fa fa-file-alt"></i></span>

              <input type="text" name="nota" class="form-control input-lg" placeholder="Ingresar nota" required>

            </div>

            

          </div>

        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

        <button type="submit" name="agregar" class="btn btn-primary">Agregar</button>

      </div>

      </form>

    </div>

  </div>
  

</div>


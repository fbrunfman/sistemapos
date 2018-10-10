<script>
  
  $(document).ready(function(){

    $(".borrar").click(function(){

      var name = $(this).attr("name");

      console.log(name);

      var data = {"name" : name};

      console.log(data);

      $.post("/delete.php", data, function(response){
        alert(response);
      });

      $(this).parent().parent().remove();

    });
  

  });

</script>

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

                include_once("conexion.php");

                 conectar();

                 global $conn;

                 // mostrar todo's de la bd

                 $query = "SELECT * FROM todo";

                 $stmt = $conn->prepare($query);

                 $stmt->execute();

                 $result = $stmt->fetchALL();

                 for ($i=0; $i < count($result) ; $i++) { 
                     echo '<li>
                    <!-- drag handle -->
                    <span class="handle">
                          <i class="fa fa-ellipsis-v"></i>
                          <i class="fa fa-ellipsis-v"></i>
                        </span>
                    <!-- checkbox -->
                    <input type="checkbox" value="">
                    <!-- todo text -->
                      
                    <span class="text" name="notaBorrar">' . $result[$i][1] . '</span>
                    <!-- General tools such as edit or delete-->
                    <div class="tools">
                        <button class="borrar" name="' . $result[$i][1] . '"><i class="fa fa-trash"></i></button>
                    </div>
                  </li>';
                 }

                 // agregar todo

                 if(isset($_POST["agregar"])){

                    $queryAdd = "INSERT INTO todo (todo) VALUES (:nota)";
                    $stmt1 = $conn->prepare($queryAdd);
                    $stmt1->bindParam(":nota", $_POST["nota"]);
                    $stmt1->execute();

                    echo '<li>
                    <!-- drag handle -->
                    <span class="handle">
                          <i class="fa fa-ellipsis-v"></i>
                          <i class="fa fa-ellipsis-v"></i>
                    </span>
                    <span class="text" name="notaBorrar">' . $_POST["nota"] . '</span>
                    <!-- General tools such as edit or delete-->
                    <div class="tools">
                      
                        <button class="borrar" name="' . $_POST["nota"] . '"><i class="fa fa-trash"></i></button>
                      
                    </div>                 
                    </li>';

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


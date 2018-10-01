<div class="content-wrapper">
    <section class="content-header">
      <h1>
        Administrar clientes
  
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-tachometer-alt"></i> Inicio</a></li>
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


        </div>
        <div class="box-body">

          <table class="table table-bordered table-striped tabla table-responsive">
            
            <thead>
              
            <tr>
              
              <th>#</th>
              <th>Nombre</th>
              <th>CUIT</th>


            </tr>

            </thead>

              <tr>
                
                <td>1</td>
                <td>Sebastian</td>
                <td>20380671329</td>

              </tr>

            <tbody>
              
            </tbody>

          </table>
          
        </div>
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

        <h4 class="modal-title">Agregar usuario</h4>

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

              <input type="text" name="nombre" class="form-control input-lg" placeholder="Ingresar CUIT" required>

            </div>

            

          </div>

        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

        <button type="submit" class="btn btn-primary">Guardar cliente</button>

      </div>

      </form>

    </div>

  </div>
</div>

<?php
class ControladorUsuarios{

    //metodo para ingreso de usuario

    public function ctrIngresoUsuario(){
        if (isset($_POST["email"])) {
        $tabla = 'usuario';
        $item = 'email';
        $valor  = $_POST["email"];

        $respuesta = ModeloUsuarios::MdlMostrarUsuarios($tabla,$item,$valor);
            var_dump($respuesta);
    }
    }



}




?>
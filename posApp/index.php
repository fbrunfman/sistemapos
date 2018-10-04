<?php 

session_start();

if (isset($_SESSION["usuario"])) {
	
	require_once "controladores/plantilla.controlador.php";

	$plantilla = new ControladorPlantilla();
	$plantilla -> ctrPlantilla();
	
} else {
	header("location:login.php");
}

?>
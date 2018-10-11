<?php  

	require_once("conexion.php");

	conectar();

	global $conn;


	$query = "DELETE FROM todo WHERE todo = '" . $_POST["name"] . "'";
	$stmt = $conn->prepare($query);
	$stmt->execute();

	echo "Item borrado exitosamente";

?>
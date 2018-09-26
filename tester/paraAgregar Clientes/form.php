<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
<?php 

// conexion

$servername = "localhost";
$username = "root";
$password = "root";
$dsn_Options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
    $conn = new PDO("mysql:host=$servername;dbname=sistemapos", $username, $password, $dsn_Options);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Se conect&oacute;";
    }
catch(PDOException $e)
    {
    echo "Fail: " . $e->getMessage();
    }

//inputs de nombre, cuit y facturacion

$nombre = $_POST['nombre'];
$cuit = $_POST['cuit'];
$facturacion = $_POST['facturacion'];

//agregar a base de datos 

$filaParaAgregar = $conn->prepare("INSERT INTO cliente (nombre, cuit, `tipo de facturacion`) VALUES (:nombre, :cuit, :facturacion)");
$filaParaAgregar->bindParam(':nombre', $nombre);
$filaParaAgregar->bindParam(':cuit', $cuit);
$filaParaAgregar->bindParam(':facturacion', $facturacion);

// se registro la fila o no

if ($filaParaAgregar->execute()) {
  echo "<br>Se registr&oacute; la nueva fila";
} else {
  echo "<br>Todo mal";
}


?>
	
</body>
</html>










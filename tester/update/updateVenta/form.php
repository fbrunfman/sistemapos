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

//inputs de detalle de la venta

$nombre = $_POST['nombre'];
$numFactura = $_POST['numFactura'];
$fechaPago = $_POST['fechaPago'];

// query para buscar cliente_id (todo esto si el id existe)

$queryId = "SELECT id from cliente WHERE nombre= '" . $nombre . "'";
$stmt = $conn->prepare($queryId);
$stmt->execute();
$result = $stmt->fetchAll();

$cliente_id = $result[0][0];

// update de la fecha de pago

$queryUpdate = "UPDATE venta SET `fecha de pago` = '" . $fechaPago . "' WHERE cliente_id = '" . $cliente_id . "' AND `numero de factura` = '" . $numFactura . "'";

//query de actualizacion

$stmt1 = $conn->prepare($queryUpdate);
$stmt1->execute();


// se registro la fila o no

if ($stmt1->execute()) {
  echo "<br>Se registr&oacute; la actualizacion";
} else {
  echo "<br>Todo mal";
}


?>
	
</body>
</html>










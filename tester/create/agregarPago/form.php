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

$razSoc = $_POST['razSoc'];
$numFactura = $_POST['numFactura'];
$fechaPago = $_POST['fechaPago'];
$pago = $_POST['pago'];

// query para sacar el compraId

$queryId = "SELECT id FROM compra WHERE `razon social` ='" . $razSoc . "' AND `numero de factura` =" . $numFactura;
$stmt = $conn->prepare($queryId);
$stmt->execute();
$result = $stmt->fetchAll();

$compraId = $result[0][0];

//agregar a base de datos 

$filaParaAgregar = $conn->prepare("INSERT INTO pago (compra_id, `fecha de pago`, pago) VALUES (:compraId, :fechaPago, :pago)");
$filaParaAgregar->bindParam(':compraId', $compraId);
$filaParaAgregar->bindParam(':fechaPago', $fechaPago);
$filaParaAgregar->bindParam(':pago', $pago);

// se registro la fila o no

if ($filaParaAgregar->execute()) {
  echo "<br>Se registr&oacute; la nueva fila";
} else {
  echo "<br>Todo mal";
}
?>
	
</body>
</html>










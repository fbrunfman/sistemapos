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

$fantasia = $_POST['fantasia'];
$razSoc = $_POST['razSoc'];
$cuitCuil = $_POST['cuitCuil'];
$numFactura = $_POST['numFactura'];
$fechaCompra = $_POST['fechaCompra'];
$tipoFactura = $_POST['tipoFactura'];
$importeTotal = $_POST['importeTotal'];
$pagoTotal = $_POST['pagoTotal'];

//agregar a base de datos 

$filaParaAgregar = $conn->prepare("INSERT INTO compra (`nombre de fantasia`, `razon social`, `cuit cuil`, `numero de factura`, `fecha`, `tipo de factura`, `importe total`, `pago total`) VALUES (:fantasia, :razSoc, :cuitCuil, :numFactura, :fechaCompra, :tipoFactura, :importeTotal, :pagoTotal)");

$filaParaAgregar->bindParam(':fantasia', $fantasia);
$filaParaAgregar->bindParam(':razSoc', $razSoc);
$filaParaAgregar->bindParam(':cuitCuil', $cuitCuil);
$filaParaAgregar->bindParam(':numFactura', $numFactura);
$filaParaAgregar->bindParam(':fechaCompra', $fechaCompra);
$filaParaAgregar->bindParam(':tipoFactura', $tipoFactura);
$filaParaAgregar->bindParam(':importeTotal', $importeTotal);
$filaParaAgregar->bindParam(':pagoTotal', $pagoTotal);

// se registro la fila o no

if ($filaParaAgregar->execute()) {
  echo "<br>Se registr&oacute; la nueva fila";
} else {
  echo "<br>Todo mal";
}
?>
	
</body>
</html>










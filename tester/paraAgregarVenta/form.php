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
$password = "";
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
$concepto = $_POST['concepto'];
$importe = $_POST['importe'];
$numFactura = $_POST['numFactura'];
$fechaVenta = $_POST['fechaVenta'];
$iva = $_POST['iva'];
$suss = $_POST['suss'];
$ganancias = $_POST['ganancias'];
$iibb = $_POST['iibb'];
$ingresoFinal = $_POST['ingresoFinal'];
$fechaPago = $_POST['fechaPago'];


//agregar a base de datos (no esta funcionando!)

$filaParaAgregar = $conn->prepare("INSERT INTO venta (cliente_id, concepto, importe, `numero de factura`, `fecha de venta`, iva, suss, ganancias, iibb, `ingreso final`, `fecha de pago`) VALUES (:cliente_id, :concepto, :importe, :numFactura, :fechaVenta, :iva, :suss, :ganancias, :iibb, :ingresoFinal, :fechaPago)");
$filaParaAgregar->bindParam(':cliente_id', $nombre);
$filaParaAgregar->bindParam(':concepto', $concepto);
$filaParaAgregar->bindParam(':importe', $importe);
$filaParaAgregar->bindParam(':numFactura', $numFactura);
$filaParaAgregar->bindParam(':fechaVenta', $fechaVenta);
$filaParaAgregar->bindParam(':iva', $iva);
$filaParaAgregar->bindParam(':suss', $suss);
$filaParaAgregar->bindParam(':ganancias', $ganancias);
$filaParaAgregar->bindParam(':iibb', $iibb);
$filaParaAgregar->bindParam(':ingresoFinal', $ingresoFinal);
$filaParaAgregar->bindParam(':fechaPago', $fechaPago);

// se registro la fila o no

if ($filaParaAgregar->execute()) {
  echo "<br>Se registr&oacute; la nueva fila";
} else {
  echo "<br>Todo mal";
}


?>
	
</body>
</html>










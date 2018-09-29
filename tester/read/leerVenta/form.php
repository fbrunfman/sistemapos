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

// query para buscar cliente_id (todo esto si el id existe)

$queryId = "SELECT id FROM cliente WHERE nombre = '" . $nombre . "'";
$stmt = $conn->prepare($queryId);
$stmt->execute();
$result = $stmt->fetchAll();

$cliente_id = $result[0][0];

//query a venta


$queryVenta = "SELECT * FROM venta WHERE cliente_id =" . $cliente_id;
$stmt = $conn->prepare($queryVenta);
$stmt->execute();
$resultVenta = $stmt->fetchAll();

for($i = 0; $i < count($resultVenta); ++$i) {
    echo "<br>Nombre del cliente: " . $nombre . "<br>Concepto: " . $resultVenta[$i]['concepto'] . "<br>Importe: " . $resultVenta[$i]['importe'] . "<br>Numero de factura: " . $resultVenta[$i]['numero de factura'] . "<br>Fecha de venta: " . $resultVenta[$i]['fecha de venta'] . "<br>IVA: " . $resultVenta[$i]['iva'] . "<br>Seguridad social: " . $resultVenta[$i]['suss'] . "<br>Impuesto a las ganancias: " . $resultVenta[$i]['ganancias'] . "<br>Ingresos brutos: " . $resultVenta[$i]['iibb'] . "<br>Ingreso final: " . $resultVenta[$i]['ingreso final'] . "<br>Fecha de pago: " . $resultVenta[$i]['fecha de pago'] . "<br><br>";
}



// se registro la fila o no

if ($filaParaAgregar->execute()) {
  echo "<br>Se registr&oacute; la nueva fila";
} else {
  echo "<br>Todo mal";
}


?>
	
</body>
</html>










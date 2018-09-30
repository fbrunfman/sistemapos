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
$desde = $_POST['desde'];
$hasta = $_POST['hasta'];

$queryId = "SELECT id FROM cliente WHERE nombre = '" . $nombre . "'";
$stmt = $conn->prepare($queryId);
$stmt->execute();
$result = $stmt->fetchAll();

$cliente_id = $result[0][0];


if ($nombre != '') {

    if ($desde == '' && $hasta == '') {

    $queryVenta = "SELECT * FROM venta WHERE cliente_id = " . $cliente_id;

    } else if ($desde == '') {

        $queryVenta = "SELECT * FROM venta WHERE cliente_id = " . $cliente_id . " AND `fecha de venta` <= '" . $hasta . "'";

    } else if ($hasta == '') {

        $queryVenta = "SELECT * FROM venta WHERE cliente_id = " . $cliente_id . " AND `fecha de venta` >= '" . $desde . "'";

    } else {

        $queryVenta = "SELECT * FROM venta WHERE cliente_id = " . $cliente_id . " AND (`fecha de venta` BETWEEN '" . $desde . "' AND '" . $hasta . "')";
    }

    $queryVenta .= " AND `fecha de pago` IS NOT NULL";

} else {

    if ($desde == '' && $hasta == '') {

    $queryVenta = "SELECT * FROM venta WHERE `fecha de pago` IS NOT NULL";

    } else if ($desde == '') {

        $queryVenta = "SELECT * FROM venta WHERE `fecha de venta` <= '" . $hasta . "' AND `fecha de pago` IS NOT NULL";

    } else if ($hasta == '') {

        $queryVenta = "SELECT * FROM venta WHERE `fecha de venta` >= '" . $desde . "' AND `fecha de pago` IS NOT NULL";

    } else {

        $queryVenta = "SELECT * FROM venta WHERE (`fecha de venta` BETWEEN '" . $desde . "' AND '" . $hasta . "') AND `fecha de pago` IS NOT NULL";
    }

}

//query

$stmt1 = $conn->prepare($queryVenta);
$stmt1->execute();
$resultVenta = $stmt1->fetchAll();

// echo query

$importe = 0;
$ingresoFinal = 0;

for($i = 0; $i < count($resultVenta); ++$i) {
    $importe += $resultVenta[$i]['importe'];
    $ingresoFinal += $resultVenta[$i]['ingreso final'];

}

echo "Importes totales: " . $importe . "<br> Ingresos finales: " . $ingresoFinal;



// se registro la fila o no

if ($filaParaAgregar->execute()) {
  echo "<br>Se registr&oacute; la nueva fila";
} else {
  echo "<br>Todo mal";
}


?>
	
</body>
</html>










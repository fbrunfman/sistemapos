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
$desde = $_POST['desde'];
$hasta = $_POST['hasta'];
$noPagas = $_POST['noPagas'];

if ($razSoc == '') {

    if ($desde == '' && $hasta == '') {

    $queryCompra = "SELECT * FROM compra";

    } else if ($desde == '') {

        $queryCompra = "SELECT * FROM compra WHERE fecha <= '" . $hasta . "'";

    } else if ($hasta == '') {

        $queryCompra = "SELECT * FROM compra WHERE fecha >= '" . $desde . "'";

    } else {

        $queryCompra = "SELECT * FROM compra WHERE (fecha BETWEEN '" . $desde . "' AND '" . $hasta . "')";
    }

} else {

    if ($desde == '' && $hasta == '') {

    $queryCompra = "SELECT * FROM compra WHERE `razon social` = '" . $razSoc . "'";

    } else if ($desde == '') {

        $queryCompra = "SELECT * FROM compra WHERE `razon social` = '" . $razSoc . "' AND fecha <= '" . $hasta . "'";

    } else if ($hasta == '') {

        $queryCompra = "SELECT * FROM compra WHERE `razon social` = '" . $razSoc . "' AND fecha >= '" . $desde . "'";

    } else {

        $queryCompra = "SELECT * FROM compra WHERE `razon social` = '" . $razSoc . "' AND (fecha BETWEEN '" . $desde . "' AND '" . $hasta . "')";
    }

}

if ($noPagas) {

    $queryCompra .= " AND `pago total` < `importe total`";

}

if ($razSoc == '' && $desde == '' && $hasta == '' && $noPagas)  {
    $queryCompra = "SELECT * FROM compra WHERE `pago total` < `importe total`";
}


//consulta 

$stmt = $conn->prepare($queryCompra);
$stmt->execute();
$result = $stmt->fetchALL();

for($i = 0; $i < count($result); ++$i) {
    echo "<br>Nombre de fantasia: " . $result[$i]['nombre de fantasia'] . "<br>Razon social: " . $result[$i]['razon social'] . "<br>CUIT/CUIL: " . $result[$i]['cuit cuil'] . "<br>Numero de factura: " . $result[$i]['numero de factura'] . "<br>Fecha de compra: " . $result[$i]['fecha'] . "<br>Tipo de factura: " . $result[$i]['tipo de factura'] . "<br>Importe total: " . $result[$i]['importe total'] . "<br>Pago total: " . $result[$i]['pago total'];
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










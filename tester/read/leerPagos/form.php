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
$factura = $_POST['factura'];

// query para buscar compra_id (todo esto si el id existe)

$queryId = "SELECT id FROM compra WHERE `numero de factura` = '" . $factura . "'" ;
$stmt = $conn->prepare($queryId);
$stmt->execute();
$result = $stmt->fetchAll();

$compra_id = $result[0][0];

//query a venta


$queryPago = "SELECT * FROM pago WHERE compra_id =" . $compra_id;
$stmt = $conn->prepare($queryPago);
$stmt->execute();
$resultPago = $stmt->fetchAll();

for($i = 0; $i < count($resultPago); ++$i) {
    echo "<br>Fecha de pago: " . $resultPago[$i]['fecha de pago'] . "<br>Pago: " . $resultPago[$i]['pago'];
}

?>
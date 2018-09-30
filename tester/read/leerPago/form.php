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

//inputs de razon social y numero de factura

$razSoc = $_POST['razSoc'];
$numFactura = $_POST['numFactura'];

//consulta de id de compra

$query = "SELECT id FROM compra WHERE `razon social`='" . $razSoc . "' AND `numero de factura` = '" . $numFactura . "'";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->fetchALL();

$compraId = $result[0][0];

// consulta a la tabla pago con compra_id

$queryPago = "SELECT * FROM pago WHERE compra_id = " . $compraId;

$stmt1 = $conn->prepare($queryPago);
$stmt1->execute();
$resultPago = $stmt1->fetchALL();

for ($i=0; $i < count($resultPago); ++$i) { 
  echo "<br>Fecha de pago: " . $resultPago[$i]['fecha de pago'] . "<br> Pago: " . $resultPago[$i]['pago'] . "<br>" ;
}

?>
	
</body>
</html>










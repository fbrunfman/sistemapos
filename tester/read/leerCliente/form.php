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

//consulta 

$query = "SELECT nombre, cuit, `tipo de facturacion` FROM cliente WHERE nombre='" . $nombre . "'";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->fetchALL();

$cuit = $result[0]['cuit'];
$tipoFacturacion = $result[0]['tipo de facturacion'];

echo "<br>Nombre: " . $nombre . "<br>CUIT: " . $cuit . "<br>Tipo de facturaci&oacute;n: " . $tipoFacturacion;

// se registro la fila o no

if ($filaParaAgregar->execute()) {
  echo "<br>Se registr&oacute; la nueva fila";
} else {
  echo "<br>Todo mal";
}
?>
	
</body>
</html>










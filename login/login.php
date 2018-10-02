<?php
session_start();


	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dsn_Options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

	try {
	    $conn = new PDO("mysql:host=$servername;dbname=sistemapos", $username, $password, $dsn_Options);
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST["login"])) {

    	if (empty($_POST["usuario"]) || empty($_POST["password"])) {

    		$message = "Todos los campos son requeridos.";

    	} else {

    		$query = "SELECT * FROM usuario WHERE nombre = :usuario AND contrasena = :password";
    		$stmt = $conn->prepare($query);
    		$stmt->execute(
    			Array(
    				'usuario' => $_POST["usuario"],
    				'password' => $_POST["password"]
    			)
    		);

    		$count = $stmt->rowCount();

    		if($count > 0){
    			$_SESSION["usuario"] = $_POST["usuario"];
    			header("location:login_success.php");
    		} else {

    			$message = "Valores incorrectos.";

    		}

    	}

   	 }

   	}

catch(PDOException $e)
    {
    echo $message = $e->getMessage();
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
</head>
<body>
	<?php 

	if (isset($message)) {
		echo $message . "<br>";
	}

	?>
	<h1>Login</h1>
		<form method="post" enctype="multipart/form-data">
			<label>Usuario</label>
			<input type="text" name="usuario" placeholder="Usuario" required>
			<br><br>
			<label>Contrase&ntilde;a</label>
			<input type="password" placeholder="Contrase&ntilde;a" name="password">
			<br><br>
			<input type="submit" name="login" value="Login">
		</form>
	
	
</body>
</html>
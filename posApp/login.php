<?php
session_start();


	$servername = "localhost";
	$username = "root";
	$password = "";
	$dsn_Options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

	try {
	    $conn = new PDO("mysql:host=$servername;dbname=sistemapos", $username, $password, $dsn_Options);
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST["login"])) {

    	if (empty($_POST["usuario"]) || empty($_POST["password"])) { ?> 

    		<div class="alert alert-danger" role="alert">
  				Todos los campos son requeridos
			</div>	

    	<?php } else {

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
    			header("location:inicio");
    		} else { ?>

    			<div class="alert alert-danger" role="alert">
  					Los campos son incorrectos
				</div> <?php

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
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

</head>
<body>
	<?php 

	if (isset($message)) {
		echo $message . "<br>";
	}

	?>

	<h1>Sistema de facturación de B&S Eventos</h1>
	<hr style="height: 12px;
    border: 0;
    box-shadow: inset 0 12px 12px -12px rgba(0, 0, 0, 0.5)">
	<img src="vistas/img/bys-logo.png" alt="">

<hr style="height: 12px;
    border: 0;
    box-shadow: inset 0 12px 12px -12px rgba(0, 0, 0, 0.5)">
	
<form  method="post" enctype="multipart/form-data">
			<div class="form-group">
			<label for="exampleInputEmail1">Email address</label>
			<input type="text" class="form-control" name="usuario" aria-describedby="emailHelp" placeholder="Usuario" required>
		</div>
		<div class="form-group">
			<label for="exampleInputPassword1">Contraseña</label>
			<input type="password" class="form-control" name="password" placeholder="Password">
		</div>
		<button type="submit" class="btn btn-primary" name="login" value="Login">Ingresá</button>
</form>
<br><br>

<hr style="height: 12px;
    border: 0;
    box-shadow: inset 0 12px 12px -12px rgba(0, 0, 0, 0.5)">

<h1>Lúcuma Diseño Web 2018 <span class="badge badge-secondary"></span></h1>
</body>
</html>
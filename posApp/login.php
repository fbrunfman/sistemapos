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

    		$password = $_POST["password"];
    		$query = "SELECT * FROM usuario WHERE nombre = :usuario";
    		$stmt = $conn->prepare($query);
    		$stmt->execute(
    			Array(
    				'usuario' => $_POST["usuario"]
    			)
    		);

    		$count = $stmt->rowCount();

    		$result = $stmt->fetchALL();


    		if($count > 0 && password_verify($password, $result[0][3])){
    			$_SESSION["usuario"] = $_POST["usuario"];
    			header("location:inicio");
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
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 
	<link rel="stylesheet" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Dosis" rel="stylesheet"> 

	
</head>
<body>

	<?php 

	if (isset($message)) {
		echo $message . "<br>";
	}

	?>




	<div class="outer">
	  	<div class="middle">
		    <div class="inner">
				
					<div class="card mb-3" style="max-width: 18rem;"  id="sinborde">
						<div class="card">
							<h1 style="text-align: center; font-family: 'Dosis', sans-serif;">SistemaPOS<br><b>B&S</b></h1>
							
							<div class="form-group">
								<div class="card-body" style="width: 18rem;" >
									
										<form method="post" enctype="multipart/form-data">
											<label>Usuario</label>
											<input type="text" name="usuario" placeholder="Usuario" required>
											<br><br>
											<label>Contrase&ntilde;a</label>
											<input type="password" placeholder="Contrase&ntilde;a" name="password">
											</div>
											<br><br>
											<script src="https://authedmine.com/lib/captcha.min.js" async></script>
										    <div class="coinhive-captcha" 
										        data-hashes="1024" 
										        data-key="GhyV4MRpmvu4dGtcV0u9y2TEzAylx1c4"
										        data-whitelabel="true"
										        data-disable-elements="input[type=submit]"
										       >
										        <em>Loading Captcha...<br>
										        If it doesn't load, please disable Adblock!</em>
										    </div>
											<div class="text-center col-ms">
												<input type="submit" class="btn btn-primary" name="login" value="Ingresar">
											</div>
										</form>
									
								</div>
							</div>
						</div>
					</div>
				
			</div>
		</div>
	</div>
</body>
</html>
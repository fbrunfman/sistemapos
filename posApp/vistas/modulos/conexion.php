<?php 

function conectar(){
	
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dsn_Options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];


	global $conn;

	try 
	{
	$conn = new PDO("mysql:host=$servername;dbname=sistemapos", $username, $password, $dsn_Options);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $conn;        
	              
	}

	catch(PDOException $e)
	{
	echo $message = $e->getMessage();
	}

	
}

?>
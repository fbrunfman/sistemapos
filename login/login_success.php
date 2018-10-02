<?php 

session_start();

if (isset($_SESSION["usuario"])) {
	echo "Login succesful, welcome" . $_SESSION["usuario"];
	echo "<a href='logout.php'>Logout</a>";
} else {
	header("location:login.php");
}



?>
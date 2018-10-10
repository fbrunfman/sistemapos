<?php
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dsn_Options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

    try {

        $conn = new PDO("mysql:host=$servername;dbname=sistemapos", $username, $password, $dsn_Options);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "INSERT INTO todo todo VALUES " . $_POST["agregar"];

        if(isset($_POST["agregar"])) {

          $query = "INSERT INTO todo todo VALUES " . $_POST["agregar"];
          $stmt = $conn->prepare($query);
          $stmt->execute();

        }
        
        }
        

        catch(PDOException $e)
          {
          echo $message = $e->getMessage();
          }

            
?>
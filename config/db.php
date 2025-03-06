<?php 

try {

    
    $dsn = "mysql:dbname=librairie;host=localhost";

    $options = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];

    $user = "root";
    $password = "root";

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $error) {

    
    die("Il y a une erreur : " . $error->getMessage());
}


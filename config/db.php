<?php 

try {

    // Ici nous avons le data source name (ou dsn) qui contient les infos relatives à notre BDD
    $dsn = "mysql:dbname=librairie;host=localhost";

    // Ici on définit nos options, juste une pour le moment qui vient faire ensorte que l'on 
    // récupère par défaut depuis la BDD les infos sous formes de tableau associatif (fetch_assoc)
    $options = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];

     // Nos identifiants de connexion à la BDD 
    $user = "root";
    $password = "";

    // Je créee une variable de connexion $pdo
    $pdo = new PDO($sdn, $user, $password, $options);

} catch(PDOException $error) {

    
    die("Il y a une erreur : " . $error->getMessage());
}


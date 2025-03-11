<?php 

session_start();

include "partials/header.php";
//include "config/db.php"



// 1 - Pouvoir chercher des livres (genre ou nom) via l'API google Books soit avec JS (fetch ou axios) soit cURL
// 2 - UN système de login et signup pour les Users (si pas login, il peut pas chercher)
// 3 - Une fois connecté le user peut ajouter des livres en favoris qui s'enregistrent en BDD. Il doit pouvoir aussi 
// supprimer ceux qu'il veut effacer => Pour tout ce qui est BDD avec PHP on utilise PDO et de requetes préparées
// 4 - Le user a un espace de profil dont il peut modifier les informations (ex : nom, email, avatar)

// Notions à utiliser en PHP : 
// - les superglobales ($_POST, $_GET, $_SESSION)
// - PDO pour se connecter à la BDD 
// - Faire des requetes SQL (et préparées si besoin)
// - cURL pour faire des requetes API (ou sinon fetch avec JS)

// Notions en JS :  
// - fetch ou axios pour le call API si pas en PHP

// php/views/search.php

?>

<h1>Rechercher des Livres</h1>
<form id="searchForm">
    <input type="text" class="search" placeholder="Titre ou Genre">
    <button class= "searchBtn">Rechercher</button>
    <button type="favBtn">Favoris</button>
</form>

<div id="results">

</div>


<?php 

include "partials/footer.php";

?>


<?php

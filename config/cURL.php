<?php 

// cURL pour faire des requetes API depuis PHP

// 1 - On initialise cURL
$ch = curl_init();

// On précise ensuite l'URL à fetcher
$url = 'http://www.omdbapi.com/?apikey=eed08b06&t=rambo';

// 2 - On précise les options à utiliser avec cURL
// On précise l'url à fetcher
curl_setopt($ch, CURLOPT_URL, $url);
// ON précise qu'on veut récupérer les données sous forme de tableau associatif
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// 3 - On éxecute cURL
$resp = curl_exec($ch);
// On convbertit depuis JSON ce que l'on récupère avec cURL
$decoded = json_decode($resp);

if(curl_error($ch)) {
    var_dump(curl_error($ch));
}

// echo "<pre>";
// print_r($decoded);
// echo "</pre>";

// 4 - On ferme la connexion
curl_close($ch);


<?php
 
/* Afficher une variable et arrête le script pour des raisons de débogage */
function dd($data) {
  echo "<pre>";
  die(var_dump($data));
  echo "</pre>";
}
 
/* Adresse de l'URL sur laquelle on va taper pour récuperer le contenu JSON */
$api_url = "https://uaco2.dev-api.cf/read/AC67B2373814/2";
/* Le contenu brut (au format JSON) issu de l'API */
$api_content = file_get_contents($api_url);
/* Transformation du JSON en Tableau PHP */
$api_content_array = json_decode($api_content);
 
/* On affiche ce que l'on a réussi à obtenir et on s'arrête ici */
dd($api_content_array);
?>
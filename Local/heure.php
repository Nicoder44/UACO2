<?php
$mac = $_GET['id']; //Variable associée à la mac adress du capteur
$url = "https://uaco2.dev-api.cf/read/$mac/1"; //Url des valeurs du capteur AC67B2373814 sur la dernière heure
$readJSONFile = file_get_contents($url); //Récupération du json du site
$tab = json_decode($readJSONFile, TRUE); //On transforme le json en un tableau
$data = json_decode($json); 
$n = sizeof($tab); //Taille du tableau
$c1 = $tab[$n-1]['co2']; //Concentration au temps 1
$c2 = $tab[$n-2]['co2']; //Concentration au temps 2
$c3 = $tab[$n-3]['co2']; //Concentration au temps 3
$c4 = $tab[$n-4]['co2']; //Concentration au temps 4
$C1 = intval($c1); 
$C2 = intval($c2);
$C3 = intval($c3);
$C4 = intval($c4);
//echo $C1, "\n";
//echo $C2, "\n";
//echo $C3, "\n";
//echo $C4, "\n";
$ylim = 800; //Concentration limite avant aération nécessaire
$cinit = 10000; //Concentration minimale à définir par la boucle suivante
for ($i=0 ; $i<=$n-1 ; $i++) {
    if($tab[$i]['co2']<$cinit){
    $cinit = $tab[$i]['co2'];
    }
}
//echo $cinit, "\n";
$deltar1 = ($C1-$C2)/5; //Variation de concentration moyenne par minute
$deltar2 = ($C2-$C3)/5; //Variation de concentration moyenne par minute
$deltar3 = ($C3-$C4)/5; //Variation de concentration moyenne par minute
$deltarmoyen = ($deltar1+$deltar2+$deltar3)/3; //Variation moyenne par minute sur les 15 dernières minutes
if($deltarmoyen<0){
    $deltarmoyen = 0.003*$C1;
}
//echo $deltar1, "\n";
//echo $deltar2, "\n";
//echo $deltar3, "\n";
//echo $deltarmoyen, "\n";
$temps = floor(60*($ylim-$cinit)/$deltarmoyen); //Temps en secondes
//echo $temps, "\n";
$heure = strtotime("now"); //Heure actuelle depuis 01/01/1970 en secondes 
//echo date("H-i", $heure), "\n";
$heureprev = $heure + $temps;
echo date("H:i", $heureprev); //Affichage de l'heure de prévision d'ouverture
?>
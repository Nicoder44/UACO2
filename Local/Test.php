<?php
$url = "https://uaco2.dev-api.cf/list";
$readJSONFile = file_get_contents($url);
$array = json_decode($readJSONFile, TRUE);
$data = json_decode($json);
$c1 = $array[9]['co2'];
$c2 = $array[10]['co2'];
$c3 = $array[11]['co2'];
var_dump($array);
exit;
?>


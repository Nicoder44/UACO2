<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=nitoco','nitoco','5iRG51wvaLyATWOgzkqS');

if(isset($_GET['ID']) AND $_GET['ID'] > 0)
{
    $getid = intval($_GET['ID']);
    $requser = $bdd->prepare('SELECT * FROM utilisateurs WHERE ID = ?');
    $requser->execute(array($getid));
    $userinfo = $requser->fetch();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset ="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>UA_CO2</title>
	<meta name="description" content="Venez lire les valeurs des capteurs de CO2 de l'UA Angers !">

	<link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css" integrity="sha384-wESLQ85D6gbsF459vf1CiZ2+rr+CsxRY0RpiF1tLlQpDnAgg6rwdsUF1+Ics2bni" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="Bienvenue.css"> 
    <link rel="shortcut icon" href="ua.jpg">
    
    <script src="raphael-2.1.4.min.js"></script>
    <script src="justgage.js"></script>
</head>

<header>
    
</header>
<body style="background: url(Fond_Angers.png);background-size: cover;">
    
    <section>
        <div align="center">
                <?php
                if(isset($_SESSION['ID']) AND $userinfo['ID'] == $_SESSION['ID'])
                {
                ?>
                    <div class="bandeaunav">
                        <a href="#" class="logo">UA CO2</a>
                        <div class="menu-toggle"></div>
                        <nav>
                            <ul> 
                                <li><a href="Test_QR2.php?ID=<?php echo $_SESSION['ID'];?>" class="active">Ajouter capteurs</a></li>
                                <li><a href="EditionProfil.php">Changer de mot de passe</a></li>
                                <li><a href="Deconnexion.php">Se déconnecter</a></li>
                            </ul>
                        </nav>
                        <div class="clearfix"></div>
                    </div>
                <?php
                }
                else
                {
                    echo "Vous n'êtes pas connecté au profil de ".$userinfo['mail'];
                }
                ?>
            <h4>Vous êtes connectés en tant que <?php echo $userinfo['mail'];?></h4>
        </div>
    </section>

    <section>
            <?php
                $reqsensor = $bdd->prepare('SELECT * FROM sensors_list WHERE userid = ?');
                $reqsensor->execute(array($_SESSION['ID']));

                $sensorinfo = $reqsensor -> fetchall();
                $sensornumber = $reqsensor -> rowCount();

                //var_dump($sensorinfo);
                //echo "<br/> Vous avez ".$sensornumber." capteurs";
                echo "<div class=\"container2\"";
                echo "<table>";
                for($i=0;$i<$sensornumber;$i++)
                {
                    $url = "https://api.rld.org/co2_ua/read/".$sensorinfo[$i]['mac']."/300";
                    $readJSONFile = file_get_contents($url);
                    $array = json_decode($readJSONFile, TRUE);
                    if(empty(end($array)['co2']))
                    {
                        end($array)['co2'] = 0;
                    }
                            /*for($j=0;$j<25;$j++)
                            {   
                                echo "<td>".$array[$j]['co2']."</td>";
                            }*/
                            echo "<div id=\"".$sensorinfo[$i]['namesensor']."\" class=\"gauge\" style=\"width:200px; height:160px; text-align: center; background: rgba(0,0,0,.6);border-radius:10px;\">";
                            echo "
                            <script>
                                var ".$sensorinfo[$i]['namesensor']." = new JustGage({
                                    id:\"".$sensorinfo[$i]['namesensor']."\",
                                    value: ".end($array)['co2'].",
                                    min: 0,
                                    max: 1200,
                                    donut : true,
                                    gaugeColor : \"rgba(0,0,0,.4)\",
                                    titleFontColor: \"#87CEEB\",
                                    valueFontColor: \"#fff\",
                                    pointer : true,
                                    pointerOptions: {
                                        toplength: -15,
                                        bottomlength: 10,
                                        bottomwidth: 12,
                                        color: '#000',
                                        stroke: '#fff',
                                        stroke_width: 3,
                                        stroke_linecap: 'round'
                                    },
                                    levelColors: [
                                    \"#AFEEEE\",
                                    \"#87CEEB\",
                                    \"#000080\"
                                    ],
                                    title: \"".$sensorinfo[$i]['namesensor']."\"
                                });
                            </script>";
                    
                            /*for($j=0;$j<25;$j++)
                            {   
                                echo "<td>".$array[$j]['ts']."</td>";
                            }*/
                    
                    //var_dump($array);
                    $readJSONFile = file_get_contents($url); //Récupération du json du site
                    $tab = json_decode($readJSONFile, TRUE); //On transforme le json en un tableau
                    $data = json_decode($json); 
                    $n = sizeof($tab); //Taille du tableau
                    $C_max = 800; //Concentration limite (en ppm) que l'on ne veut pas dépasser
                    $c1 = $tab[$n-1]['co2']; //Concentration au temps 1
                    $c2 = $tab[$n-2]['co2']; //Concentration au temps 2
                    $c3 = $tab[$n-3]['co2']; //Concentration au temps 3
                    $c4 = $tab[$n-4]['co2']; //Concentration au temps 4
                    $c5 = $tab[$n-5]['co2']; //Concentration au temps 5
                    $c6 = $tab[$n-6]['co2']; //Concentration au temps 6
                    $C1 = intval($c1); 
                    $C2 = intval($c2);
                    $C3 = intval($c3);
                    $C4 = intval($c4);
                    $C5 = intval($c5);
                    $C6 = intval($c6);
                    $R = 2.7;
                    $t6 = 0;
                    $t5 = 1/12;
                    $t4 = 2/12;
                    $t3 = 3/12;
                    $t2 = 4/12;
                    $t1 = 5/12;
                    $a_tilde = $C1*exp(-$R*$t1)+$C2*exp(-$R*$t2)+$C3*exp(-$R*$t3)+$C4*exp(-$R*$t4)+$C5*exp(-$R*$t5)+$C6*exp(-$R*$t6);
                    $b_tilde = $C1+$C2+$C3+$C4+$C5+$C6;
                    $a = exp(-$t1*$R)*exp(-$t1*$R)+exp(-$t2*$R)*exp(-$t2*$R)+exp(-$t3*$R)*exp(-$t3*$R)+exp(-$t4*$R)*exp(-$t4*$R)+exp(-$t5*$R)*exp(-$t5*$R)+exp(-$t6*$R)*exp(-$t6*$R);
                    $b = exp(-$t1*$R)+exp(-$t2*$R)+exp(-$t3*$R)+exp(-$t4*$R)+exp(-$t5*$R)+exp(-$t6*$R);
                    $d = 6;
                    $det = $a*$d-$b*$b;
                    $alpha = ($d*$a_tilde-$b*$b_tilde)/$det;
                    $beta = ($a*$b_tilde-$b*$a_tilde)/$det;
                    $deltar1 = ($C1-$C2)/5;
                    $deltar2 = ($C2-$C3)/5;
                    $deltar3 = ($C3-$C4)/5;
                    $deltar4 = ($C4-$C5)/5;
                    $deltar5 = ($C5-$C6)/5;
                    $deltarm = ($deltar1+$deltar2+$deltar3+$deltar4+$deltar5)/5;
                    $Cm1 = $C1+$C2+$C3;
                    $Cm2 = $C4+$C5+$C6;
                    $C_moyen = ($Cm1+$Cm2)/6;
                    $err=0;
                    if($deltarm<2 && $C_moyen>650)
                    {
                    $alpha = -9167; //Valeur de alpha pour que l'on rajoute arbitrairement 1h au temps de prévision
                    $beta = 1000; //Valeur de beta pour que l'on rajoute arbitrairement 1h au temps de prévision et que beta>c_max
                    }
                    if($C1>($C_max-20) && $err==0)
                    {
                    echo "<p style=\"color:red;\">Aération nécessaire maintenant</p>";
                    $err=1;
                    }
                    if($C_max>$beta && $err==0)
                    {
                    echo "<p style=\"color:#00FF00;\">Pas d'ouverture nécessaire prochainement</p>";
                    $err=1;
                    }
                    if($deltarm<2 && $C_moyen<650 && $err==0)
                    {
                    echo "<p style=\"color:#00FF00;\">Pas d'ouverture nécessaire prochainement</p>";
                    $err=1;
                    }
                    if($err==0)
                    {
                    $temps = floor(3600*(-log(($C_max-$beta)/$alpha)/2.7))-1500;
                    $heure = strtotime("now"); //Heure actuelle depuis 01/01/1970 en secondes 
                    $heureprev = $heure + $temps;
                    echo "<p style=\"color:orange;\">".date("H:i", $heureprev)."</p>";
                    }
                    
                    ?>
                    
                    <?php
                    echo "<a class=\"courbe\" href=\"Courbe.php?mac=".$sensorinfo[$i]['mac']."&userid=".$sensorinfo[$i]['userid']."\">".$sensorinfo[$i]['namesensor']."</a>";
                    echo "</table></div>";
                }
            ?>    
    </section>
<!-- JS -->
    <script src="jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.menu-toggle').click(function(){
                $('.menu-toggle').toggleClass('active')
                $('nav').toggleClass('active')
            })
        })
    </script>
    <script src="https://kit.fontawesome.com/6312d3009a.js" crossorigin="anonymous"></script>
    <script src="bootstrap.min.js"></script>
    <script src="Bienvenue.js"></script>
</body>
<footer>
</footer>


</html>
<?php
}
?>
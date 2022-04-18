<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=nitoco','nitoco','5iRG51wvaLyATWOgzkqS');

    if(isset($_GET['mac']))
    {
        if(isset($_GET['userid']) AND $_GET['userid']==$_SESSION['ID'])
        {
            $getid = intval($_GET['userid']);
            $mac = htmlspecialchars($_GET['mac']);
            $reqsensor = $bdd->prepare('SELECT * FROM sensors_list WHERE userid = ? AND mac = ?');

            $reqsensor->execute(array($getid, $mac));
            $sensorexist = $reqsensor->rowCount();
            if($sensorexist == 1)
            {
                $url = "https://api.rld.org/co2_ua/read/".$mac."/300";
                $readJSONFile = file_get_contents($url);
                $array = json_decode($readJSONFile, TRUE);
                $n = sizeof($array);
                $v1 = $array[$n-1]['co2'];
                $v2 = $array[$n-2]['co2'];
                $v3 = $array[$n-3]['co2'];
                $v4 = $array[$n-4]['co2'];
                $v5 = $array[$n-5]['co2'];
                $v6 = $array[$n-6]['co2'];
                $v7 = $array[$n-7]['co2'];
                $v8 = $array[$n-8]['co2'];
                $v9 = $array[$n-9]['co2'];
                $v10 = $array[$n-10]['co2'];

                $t1 = $array[$n-1]['ts'];
                $t2 = $array[$n-2]['ts'];
                $t3 = $array[$n-3]['ts'];
                $t4 = $array[$n-4]['ts'];
                $t5 = $array[$n-5]['ts'];
                $t6 = $array[$n-6]['ts'];
                $t7 = $array[$n-7]['ts'];
                $t8 = $array[$n-8]['ts'];
                $t9 = $array[$n-9]['ts'];
                $t10 = $array[$n-10]['ts'];

                $T1 = strtotime($t1);
                $T2 = strtotime($t2);
                $T3 = strtotime($t3);
                $T4 = strtotime($t4);
                $T5 = strtotime($t5);
                $T6 = strtotime($t6);
                $T7 = strtotime($t7);
                $T8 = strtotime($t8);
                $T9 = strtotime($t9);
                $T10 = strtotime($t10);

                
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset ="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>UA_CO2</title>
	<meta name="description" content="Venez lire les valeurs des capteurs de CO2 de l'UA Angers !">

	<link rel="stylesheet" href="bootstrap.min.css">
    <link href="c3.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css" integrity="sha384-wESLQ85D6gbsF459vf1CiZ2+rr+CsxRY0RpiF1tLlQpDnAgg6rwdsUF1+Ics2bni" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="Bienvenue.css"> 
    <link rel="shortcut icon" href="ua.jpg">
    
    
    <script src="d3.min.js"></script>
    <script src="c3.min.js"></script>
</head>

<header>
    
</header>
<body style="background: url(Fond_Angers.png);background-size: cover;">

<section>
        <div align="center">
                    <div class="bandeaunav">
                        <a href="#" class="logo">UA CO2</a>
                        <div class="menu-toggle"></div>
                        <nav>
                            <ul> 
                                <li><a href="Test_QR2.php?ID=<?php echo $_SESSION['ID'];?>" class="active">Ajouter capteurs</a></li>
                                <li><a href="EspaceMembre.php?ID=<?php echo $_SESSION['ID'];?>">Mon profil</a></li>
                                <li><a href="EditionProfil.php">Changer de mot de passe</a></li>
                                <li><a href="Deconnexion.php">Se déconnecter</a></li>
                            </ul>
                        </nav>
                        <div class="clearfix"></div>
                    </div></br></br>
                    <section align="center" style="width:1000px;height:350px;background:rgba(255, 255, 255, 0.342);;border-radius:30px;">
                        <div id="chart"></div>
                        <script type="text/javascript">
                        var chart = c3.generate({
                            data: {
                                x: 'x',
                                xFormat: '%Y',
                                columns: [
                        //            ['x', '2012-12-31', '2013-01-01', '2013-01-02', '2013-01-03', '2013-01-04', '2013-01-05'], 
                                    ['x', '<?php echo date("H:i", $T10);?>', '<?php echo date("H:i",$T9);?>','<?php echo date("H:i",$T8);?>','<?php echo date("H:i",$T7);?>','<?php echo date("H:i",$T6);?>','<?php echo date("H:i",$T5);?>','<?php echo date("H:i",$T4);?>','<?php echo date("H:i",$T3);?>','<?php echo date("H:i",$T2);?>','<?php echo date("H:i",$T1);?>'],
                                    ['CO2 en ppm', <?php echo $v10;?>, <?php echo $v9;?>,<?php echo $v8;?>,<?php echo $v7;?>,<?php echo $v6;?>,<?php echo $v5;?>,<?php echo $v4;?>,<?php echo $v3;?>,<?php echo $v2;?>,<?php echo $v1;?>]
                                ]
                            },
                            axis: {
                                x: {
                                    type: 'category',
                                    // if true, treat x value as localtime (Default)
                                    // if false, convert to UTC internally
                                    localtime: true,
                                    
                                }
                            }
                        });
                        </script>
                    </section>
        </div>
</section>


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
else
{
    echo "Erreur, le capteur semble ne pas exister ou ne pas vous appartenir";
}
}
else
{
echo "Erreur, il semblerait que ce capteur ne vous appartienne pas, vous ne pouvez par conséquent pas avoir accès à sa courbe";
}
}
else
{
echo "Erreur, pas de mac adresse";
}
?>

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
    
</head>

<header>
    
</header>
<body style="background: url(Fond_Angers.jpg);background-size: cover;">
    
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
                                <li><a href="index.php" class="active">Page d'accueil</a></li>
                                <li><a href="EspaceMembre.php?ID=<?php echo $_SESSION['ID'];?>">Mon profil</a></li>
                                <li><a href="EditionProfil.php">Editer mon profil</a></li>
                                <li><a href="Deconnexion.php">Se déconnecter</a></li>
                            </ul>
                        </nav>
                        <div class="clearfix"></div>
                    </div>
                <?php
                }
                else
                {
                    echo "Vous n'êtes pas connecté au profil de ".$userinfo['username'];
                }
                ?>
        </div>
    </section>
    <?php
        if(isset($_SESSION['ID']) AND $userinfo['ID'] == $_SESSION['ID'])
        {
        ?>
    <div class="container">
        <div class ="row">
            <div class = "col-lg-6 col-md-12 col-sm-12">
                <video id="preview" width="100%"></video>
            </div>
            <div class = "col-lg-6 col-md-12 col-sm-12">
                <div class="formulaire">
                    <form>
                        <div class="inputBox">
                            <input type="text" name="text2" id="text2" readonly="" class="form-control">
                            <label id="label2"for="text2">Lien du QR-Code</label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
         }
    ?>
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
    <!--<script src="instascan.min.js"></script>-->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script type="text/javascript">
        let scanner=new Instascan.Scanner({video:document.getElementById('preview')});
        Instascan.Camera.getCameras().then(function(cameras){
            if(cameras.length>0)
            {
                scanner.start(cameras[0]);
            }
            else
            {
                alert("Pas de caméra trouvée");
            }
        }).catch(function(e)
        {
            console.error(e);
        });
        scanner.addListener('scan',function(c){
            document.getElementById("text2").value=c;
        });
    </script>
    <script type="text/javascript">
    
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
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset ="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>UA_CO2</title>
	<meta name="description" content="Venez lire les valeurs des capteurs de CO2 de l'UA Angers !">

	<link rel="stylesheet" href="bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="Bienvenue.css"> 
    <link rel="shortcut icon" href="ua.jpg">
    
</head>

<header>
    
</header>
<body style="background: url(Fond_Angers.png);background-size: cover;">
<div align="center">
<?php
    if(isset($_GET['mail'], $_GET['key']) AND !empty($_GET['mail']) AND !empty($_GET['key']))
    {
        $bdd = new PDO('mysql:host=localhost;dbname=nitoco','nitoco','5iRG51wvaLyATWOgzkqS');
        $pseudo = htmlspecialchars(urldecode($_GET['mail']));
        $key = $_GET['key'];

        $requser = $bdd->prepare("SELECT * FROM utilisateurs WHERE mail = ? AND confirmkey = ?");
        $requser -> execute(array($pseudo, $key));
        $userexist = $requser->rowCount();

        if($userexist == 1)
        {
            $user = $requser -> fetch();
            if($user['confirme'] == 0)
            {
                $updateuser = $bdd-> prepare("UPDATE utilisateurs SET confirme = 1 WHERE mail = ? AND confirmkey = ?");
                $updateuser -> execute(array($pseudo, $key));
                echo '</br>'.'</br>'.'</br>'.'</br>';
                echo '<font size="4em" color="skyblue">'."Votre compte est désormais actif !".'</font>';
                echo '</br>';
                echo '<a href="Connexion.php">'."Je me connecte !".'</a>';
            }
            else
            {
                echo '<font size="4em" color="skyblue">'."Votre compte est déja validé".'</font>';
            }
        }
        else
        {
            echo '<font size="4em" color="skyblue">'."Erreur d\'authentification".'</font>';
        }
    }
    
?>
</div>
<!-- JS -->
<script src="bootstrap.min.js"></script>
    <script src="Bienvenue.js"></script>
</body>
<footer>

</footer>


</html>
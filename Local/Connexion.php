<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=nitoco','nitoco','5iRG51wvaLyATWOgzkqS');

if(isset($_POST['formconnect']))
{
    $mailconnect = htmlspecialchars($_POST['mailconnect']);
    $passwordconnect = sha1($_POST['passwordconnect']);
    if(!empty($mailconnect) AND !empty($passwordconnect))
    {
        $requser = $bdd->prepare("SELECT * FROM utilisateurs WHERE mail = ? AND motdepasse = ?");
        $requser -> execute(array($mailconnect, $passwordconnect));
        $userexist = $requser->rowCount();

        if($userexist == 1)
        {
            $userinfo = $requser->fetch();
            $verifie = $userinfo['confirme'];
            if($verifie == 1)
            {
                $_SESSION['ID'] = $userinfo['ID'];
                $_SESSION['mail'] = $userinfo['mail'];
                header("Location: EspaceMembre.php?ID=".$_SESSION['ID']); 
            }
            else
            {
                $erreur = "Votre compte doit encore être vérifié !";
            }
            
        }
        else
        {
            $erreur = "Mail ou mot de passe incorrect";
        }
    }
    else
    {
        $erreur = "Tous les champs doivent être remplis";
    }
}

?>

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
    <br /><br /><br /><br />
    <section>
        <div align="center" class="formulaire">
            <h2>Connexion à UA CO2</h2>
            <h3><span>B</span>ienvenue !</h3>
            <br /><br />
            <form method="POST" action="">
                        <div class="inputBox">
                            <input type="text" name="mailconnect" id="mailconnect" required="">
                            <label for="mailconnect">Adresse mail :</label>
                        </div>
                        <div class="inputBox">
                            <input type="password" name="passwordconnect" id="passwordconnect" required="">
                            <label for="passwordconnect">Mot de passe :</label>
                        </div>
                        
                <input type="submit" value="Se connecter" name ="formconnect"></br>
                <a href="Inscription.php">S'inscrire</a>
            </form>
            <?php
                if(isset($erreur))
                {
                    echo '<font color="#00FFFF">'.$erreur.'</fonts>';
                }
            ?>
        </div>
    </section>
<!-- JS -->
    <script src="bootstrap.min.js"></script>
    <script src="Bienvenue.js"></script>
</body>
<footer>
</footer>


</html>
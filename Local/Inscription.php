<?php

$bdd = new PDO('mysql:host=localhost;dbname=nitoco','nitoco','5iRG51wvaLyATWOgzkqS');

if(isset($_POST['forminscription']))
{
    $mail = htmlspecialchars($_POST['mail']);
    $password = sha1($_POST['password']);
    $password2 = sha1($_POST['password2']);
    $testmail = $bdd->prepare("SELECT * FROM utilisateurs WHERE mail = ?");
    $testmail -> execute(array($mail));
    $mailok = $testmail -> rowCount();

    if(!empty($_POST['mail']) AND !empty($_POST['password']) AND !empty($_POST['password2']))
    {
            if($mailok == 0)
            {
                if(filter_var($mail, FILTER_VALIDATE_EMAIL))
                {
                    if($password == $password2)
                    {
                        $key = md5(microtime(TRUE)*100000);

                        $insertmembre = $bdd->prepare("INSERT INTO utilisateurs(motdepasse, mail, confirmkey) VALUES (?, ?, ?)");
                        $insertmembre -> execute(array($password,$mail,$key));

                        $header="MIME-Version: 1.0\r\n";
                        $header.='From:"UA CO2"<nicomace@univ-angers.fr>'."\n";
                        $header.='Content-Type:text/html; charset="UTF-8"'."\n";
                        $header.='Content-Transfer-Encoding: 8bit';

                        $message='
                        <html>
                            <body>
                                <div align="center">
                                    <p>
                                        Bonjour, nous vous souhaitons la bienvenue sur la plateforme UA CO2 <br>
                                        avant de pouvoir l\'utiliser, veuillez cliquer sur le lien ci-dessous afin de valider qu\'il s\'agit bien de votre adresse mail.<br>
                                        Une fois validé, votre compte vous permettra de consulter le taux de CO2 de vos capteurs, que vous devez lier à votre compte via l\'onglet "ajouter capteurs" du site.<br>
                                        Pour ce faire vous pouvez soit utiliser le scanner de QR code du site (n\'oubliez pas d\'autoriser l\'utilisation de votre caméra), ou simplement entrer la MAC adresse fournie sur votre capteur dans la barre prévue.<br>
                                    </p>
                                </div>
                                <div align="center">
                                    <a href = "https://nitoco.u-angers.fr/UA_CO2/Confirmation.php?mail='.urlencode($mail).'&key='.$key.'">Confirmez votre compte ici !</a>
                                </div>
                            </body>
                        </html>
                        ';

                        mail($mail , "UA CO2 vous souhaite la bienvenue ! Confirmez votre compte !", $message, $header);
                        $erreur = "Votre compte est bien créé ! Pensez à l'activer via le mail qui vous a été envoyé !<a href= \"Connexion.php\" >Me connecter</a>";
                    }
                    else
                    {
                        $erreur = "Vos mots de passe ne correspondent pas";
                    }
                }
                else
                {
                    $erreur = "Votre adresse mail n'est pas valide";
                }
            }else
            {
                $erreur = "Le mail sélectionné existe déja";
            }   
        
    }
    else
    {
        $erreur = "Tous les champs doivent etre remplis";
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
            <h2>Nouveau sur UA CO2 ?</h2>
            <h3><span>B</span>ienvenue !</h3>
            <br /><br />
            <form method="POST" action="">
                        <div class="inputBox">  
                            <input type="email" name="mail" id="mail" value ="<?php if(isset($mail)){echo $mail;}?>" required="">
                            <label for="mail">Adresse mail :</label>
                        </div>
                        <div class="inputBox">
                            <input type="password" name="password" id="password" required="">
                            <label for="password">Mot de passe :</label>
                        </div>
                        <div class="inputBox">  
                            <input type="password" name="password2" id="password2" required="">
                            <label for="password2">Confirmation mot de passe :</label>
                        </div>
                <input type="submit" value="Envoyer" name ="forminscription"></br>
                <a href="Connexion.php">J'ai déja un compte</a>
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
<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=nitoco','nitoco','5iRG51wvaLyATWOgzkqS');

if(isset($_SESSION['ID']))
{
    $requser = $bdd->prepare("SELECT * FROM utilisateurs WHERE ID = ?");
    $requser -> execute(array($_SESSION['ID']));
    $user = $requser->fetch();


    if(isset($_POST['newmdp1']) AND !empty($_POST['newmdp1']) AND isset($_POST['newmdp2']) AND !empty($_POST['newmdp2']))
    {
        $mdp1 = sha1($_POST['newmdp1']);
        $mdp2 = sha1($_POST['newmdp2']);

        if($mdp1 == $mdp2)
        {
            $insertmdp = $bdd->prepare("UPDATE utilisateurs SET motdepasse = ? WHERE ID = ?");
            $insertmdp->execute(array($mdp1, $_SESSION['ID']));
            header('Location: EspaceMembre.php?ID='.$_SESSION['ID']);
        }
        else
        {
            $erreur = "Vos mots de passe ne correspondent pas";
        }
    }
    else if(isset($_POST['newmdp1']) AND empty($_POST['newmdp1']) AND empty($_POST['newmdp2']))
    {
        header('Location: EspaceMembre.php?ID='.$_SESSION['ID']);
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
    
    <section>
        <div align="center" class="formulaire">
            <h2>Mode Edition</h2>
            <form method="POST" action="">
                <div class="inputBox">
                    <input type="password" name="newmdp1" id="newmdp1">
                    <label for="newmdp1">Nouveau mot de passe : </label>
                </div>
                <div class="inputBox">
                    <input type="password" name="newmdp2" id="newmdp2">
                    <label for="newmdp2">Nouveau mot de passe (confirmation) : </label>
                </div>
                <input type="submit" value="Mettre à jour mon profil">
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
<?php
}
else
{
    header("Location: Connexion.php");
}
?>
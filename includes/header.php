<?php
// header.php est inclus dans des pages qui démarrent déjà la session donc il n'est pas utile de faire un SESSION_START ici.

//Variable d'affichage du menu de navigation
$nav = "";

//Désormais je dois changer mon menu de naviagtion en fonction du fait que l'utilisateur est connecté ou non:
if(isset($_SESSION['id'])){
    //Le menu lorsque l'utilisateur est connecté en tant que membre
    $nav = "<p>Bonjour {$_SESSION['prenom']}</p>
            <a href='../index.php'>Acceuil</a>
            <a href='../ajout-jeu.php'>Ajouter un jeu</a>
            <a href='../mon-compte.php'>Mon compte</a>
            <a href='../deconnexion.php'>Se déconnecter</a>";
} else {
    //Le menu lorsque l'utilisateur n'est pas connecté en tant que membre
    $nav = "<a href='../index.php'>Acceuil</a>
            <a href='../inscription.php'>Inscription</a>
            <a href='../connexion.php'>Connexion</a>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ludothèque</title>
    <link rel="stylesheet" href="../src/css/style.css">
</head>
<body>
        <header>
        <nav>
            <?php echo($nav) ?>
        </nav>
    </header>
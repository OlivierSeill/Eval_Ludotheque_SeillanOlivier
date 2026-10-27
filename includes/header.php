<?php
// header.php est inclus dans des pages qui démarrent déjà la session donc il n'est pas utile de faire un SESSION_START ici.

//Variable d'affichage du menu de navigation
$nav = "";

//Pour l'instant le menu de base n'a pas encore besoin de vérifier si l'utilisateur est connecté ou non.
$nav = "<a href='../index.php'>Acceuil</a>
        <a href='../inscription.php'>Inscription</a>
        <a href='../connexion.php'>Connexion</a>";

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
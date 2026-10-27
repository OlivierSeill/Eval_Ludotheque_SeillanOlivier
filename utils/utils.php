<?php
/*
Dans utils/utils.php, créer la fonction formatJoueurs(). Elle prend en paramètres le nombre minimum et le nombre maximum de joueurs d’un jeu, et retourne une chaîne de caractères :
–	« Solo » si le jeu se joue uniquement à 1 joueur
–	« X joueurs » si le minimum et le maximum sont égaux (exemple : « 2 joueurs »)
–	« X à Y joueurs » dans les autres cas (exemple : « 2 à 4 joueurs »)
*/

function formatJoueurs($joueursMin,$joueursMax){
    $formatJoueurs = "";
    if ($joueursMax == 1) {
        $formatJoueurs = "Solo";
    } else if ($joueursMax == $joueursMin) {
        $formatJoueurs = "{$joueursMax} joueurs";
    } else {
        $formatJoueurs = "{$joueursMin} à {$joueursMax} joueurs";
    }
    return $formatJoueurs;
}

/*
Toujours dans utils/utils.php, créer la fonction categorieDuree(). Elle prend en paramètre la durée d’une partie en minutes, et retourne :
–	« Partie rapide » si la durée est inférieure à 30 minutes
–	« Partie moyenne » si la durée est comprise entre 30 et 90 minutes inclus
–	« Partie longue » au-delà de 90 minutes
*/

function categorieDuree($duree){
    $categorieDuree = "";
    if ($duree <= 30) {
        $categorieDuree = "Partie rapide";
    } else if ($duree >= 90) {
        $categorieDuree = "Partie longue";
    } else {
        $categorieDuree = "Partie moyenne";
    }
    return $categorieDuree;
}

?>
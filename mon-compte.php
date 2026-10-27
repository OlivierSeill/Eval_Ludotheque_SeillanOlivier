<?php
session_start();

/*
La page mon-compte.php est réservée aux membres connectés redirection vers connexion.php si non connecté
*/
if(!isset($_SESSION['id'])){
    header("Location:connexion.php");;
}

//Je mets en place mes variables d'affichage
$detailsMembre = "";
$listeJeux = "";

//Je me connecte à la BDD et je configure l'affichage des données du membre
try{
    $pdo = new PDO('mysql:host=localhost;dbname=ludotheque','root','',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $request = $pdo->prepare('SELECT m.prenom, m.nom, m.email, m.date_inscription FROM membre m
    WHERE id_membre=?;');

    $request->bindParam(1,$_SESSION['id'],PDO::PARAM_STR);
    $request->execute();
    $data = $request->fetchAll();

    $detailsMembre = $detailsMembre."<li>Prenom : {$data[0]['prenom']}</li><li>Nom : {$data[0]['nom']}</li><li>Email : {$data[0]['email']}</li><li>Date d'inscription : {$data[0]['date_inscription']}</li>";

}catch(EXCEPTION $error){
    die($error->getMessage());
}

//Je me connecte à la BDD et je configure l'affichage des jeux du membre
include('utils/utils.php');

try{
    $pdo = new PDO('mysql:host=localhost;dbname=ludotheque','root','',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $request = $pdo->prepare('SELECT j.titre, c.nom_categorie, j.joueurs_min, j.joueurs_max, j.duree FROM jeu j
    INNER JOIN membre m ON m.id_membre = j.id_membre
    INNER JOIN categorie c ON c.id_categorie = j.id_categorie
    WHERE j.id_membre=?
    ORDER BY j.titre;');

    $request->bindParam(1,$_SESSION['id'],PDO::PARAM_STR);
    $request->execute();
    $data = $request->fetchAll();

    foreach($data as $tab){
        $formatJoueurs = formatJoueurs($tab['joueurs_min'],$tab['joueurs_max']);
        $formatDuree = categorieDuree($tab['duree']);
        $listeJeux = $listeJeux."<li>{$tab["titre"]} : {$tab["nom_categorie"]} - {$formatJoueurs} - {$tab['duree']} min. ({$formatDuree}).</li>";
    }

}catch(EXCEPTION $error){
    die($error->getMessage());
}

include('includes/header.php');
?>

<main>
        <section>
            <h1>Mon compte</h1>
            <h2>Mes informations :</h2>
            <ul>
                <?php echo($detailsMembre) ?>
            </ul>
            <h2>Mes Jeux :</h2>
            <ul>
                <?php echo($listeJeux) ?>
            </ul>
        </section>
    </main>

<?php
    //J'inclus le footer à ma page
    include('includes/footer.php');
?>
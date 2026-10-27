<?php
session_start();

/*
Partie3/3)	Dans index.php, récupérer en base de données la liste de tous les jeux, triés par ordre alphabétique de titre, et l’afficher. Pour chaque jeu, afficher :
–	son titre et le nom de sa catégorie
–	le nombre de joueurs, mis en forme avec formatJoueurs()
–	sa durée en minutes, suivie du résultat de categorieDuree()
–	le prénom du membre qui l’a ajouté
*/
//J'inclus le fichier utils.php car je vais avoir besoin des fonctions créées dedans
include('utils/utils.php');

//Je mets en place ma variable d'affichage
$listeLudotheque = "";

//Je me connecte à la BDD et je configure l'affichage des données
try{
    $pdo = new PDO('mysql:host=localhost;dbname=ludotheque','root','',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $request = $pdo->prepare('SELECT j.titre, c.nom_categorie, j.joueurs_min, j.joueurs_max, j.duree, m.prenom FROM jeu j
    INNER JOIN categorie c ON c.id_categorie = j.id_categorie
    INNER JOIN membre m ON m.id_membre = j.id_membre
    ORDER BY j.titre;');

    $request->execute();

    $data = $request->fetchAll(PDO::FETCH_ASSOC);

    foreach($data as $tab){
        $formatJoueurs = formatJoueurs($tab['joueurs_min'],$tab['joueurs_max']);
        $formatDuree = categorieDuree($tab['duree']);
        $listeLudotheque = $listeLudotheque."<li>{$tab["titre"]} : {$tab["nom_categorie"]} - {$formatJoueurs} - {$tab['duree']} min. ({$formatDuree}) - Ajouté par {$tab['prenom']}.</li>";
    }

}catch(EXCEPTION $error){
    die($error->getMessage());
}


include('includes/header.php');
?>

    <main>
        <section>
            <h1>Accueil Ludothèque</h1>
            <p>Bienvenu sur la ludothèque !</p>
            <h2>Voici la liste de nos jeux :</h2>
            <ul>
            <?php echo($listeLudotheque) ?>
            </ul>
        </section>
    </main>

<?php
    //J'inclus le footer à ma page
    include('includes/footer.php');
?>
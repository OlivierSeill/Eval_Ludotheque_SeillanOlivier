<?php
session_start();

/*
La page ajout-jeu.php est réservée aux membres connectés : si personne n’est connecté, rediriger immédiatement vers connexion.php.
*/
if(!isset($_SESSION['id'])){
    header("Location:connexion.php");;
}

//Je mets en place ma variable d'affichage
$messageAjout = "";

//Les options de la liste catégorie sont générées à partir des catégories enregistrées en base de données : chaque option affiche le nom de la catégorie et a pour valeur son id.

//Je mets en place ma variable d'affichage
$optionsCategorie = "";

//Je me connecte à la BDD et je configure l'affichage des données
try{
    $pdo = new PDO('mysql:host=localhost;dbname=ludotheque','root','',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $request = $pdo->prepare('SELECT c.nom_categorie, c.id_categorie FROM categorie c ORDER BY c.nom_categorie;');

    $request->execute();

    $data = $request->fetchAll(PDO::FETCH_ASSOC);

    foreach($data as $tab){
        $optionsCategorie = $optionsCategorie."<option value='{$tab['id_categorie']}'>{$tab['nom_categorie']}</option>";
    }

}catch(EXCEPTION $error){
    die($error->getMessage());
}


//Réception du formulaire
if(isset($_POST['submitAjout'])){
    //Vérifier si tous les champs sont remplis
    if(!empty($_POST['titre']) && $_POST['joueursMin'] != "" && $_POST['joueursMax'] != "" &&  $_POST['duree'] != "" && $_POST['categorie'] != ""){

//Vérification du format des données
        // Les nombres de joueurs, la durée et la catégorie sont des nombres entiers
        if(filter_var($_POST['joueursMin'], FILTER_VALIDATE_INT) !== false && filter_var($_POST['joueursMax'], FILTER_VALIDATE_INT) !== false && filter_var($_POST['duree'], FILTER_VALIDATE_INT) !== false && filter_var($_POST['categorie'], FILTER_VALIDATE_INT) !== false){


            //le nombre minimum de joueurs est au moins égal à 1
            if ($_POST['joueursMin'] >= 1) {

                //Le nombre maximum de joueurs est supérieur ou égal au nombre minimum
                if ($_POST['joueursMax'] >= $_POST['joueursMin']) {
                    
                    //La durée est supérieure à 0
                    if($_POST['duree'] > 0){

                        //Je nettoie avec trim()
                        $titre = trim($_POST['titre']);

                        //Et je récupére les données dans des variables pour plus de clarté
                        $joueursMin = $_POST['joueursMin'];
                        $joueursMax = $_POST['joueursMax'];
                        $duree = $_POST['duree'];
                        $idCategorie = $_POST['categorie'];

                        //BONUS : vérification que la catégorie existe :
                        try{
                            $pdo = new PDO('mysql:host=localhost;dbname=ludotheque','root','',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

                            $requestExistCat = $pdo->prepare('SELECT c.nom_categorie FROM categorie c WHERE id_categorie=?;');
                            $requestExistCat->bindParam(1,$idCategorie,PDO::PARAM_STR);
                            $requestExistCat->execute();
                            $existingCat = $requestExistCat->fetchAll();

                            //Si la requête n'est pas vide la catégorie existe bien, je poursuis donc le processus 
                            if (!empty($existingCat)) {
                                
                                 //Vérification qu'aucun jeu portant le même titre n’existe déjà dans le catalogue
                                try{
                                    $pdo = new PDO('mysql:host=localhost;dbname=ludotheque','root','',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

                                    $requestExist = $pdo->prepare('SELECT j.titre FROM jeu j WHERE titre=?;');
                                    $requestExist->bindParam(1,$titre,PDO::PARAM_STR);
                                    $requestExist->execute();
                                    $existingJeu = $requestExist->fetchAll();

                                    //Si la requête est vide le jeu n'existe pas encore je peux donc l'enregister
                                    if (empty($existingJeu)) {
                                
                                        $requestInsert=$pdo->prepare("INSERT INTO jeu (titre,joueurs_min, joueurs_max, duree, id_categorie, id_membre)
                                        VALUES (?,?,?,?,?,?);");

                                        $requestInsert->bindParam(1,$titre,PDO::PARAM_STR);
                                        $requestInsert->bindParam(2,$joueursMin,PDO::PARAM_STR);
                                        $requestInsert->bindParam(3,$joueursMax,PDO::PARAM_STR);
                                        $requestInsert->bindParam(4,$duree,PDO::PARAM_STR);
                                        $requestInsert->bindParam(5,$idCategorie,PDO::PARAM_STR);
                                        $requestInsert->bindParam(6,$_SESSION['id'],PDO::PARAM_STR);

                                        $requestInsert->execute();

                                        $messageAjout="{$_SESSION['prenom']} votre jeu a bien été ajouté!";

                                    } else {
                                        //Message si le jeu existe déjà
                                        $messageAjout="Ce jeu existe déjà!";
                                    }

                                }catch(EXCEPTION $error){
                                    die($error->getMessage());
                                }
                                

                            } else {
                                //Message si la catégorie n'existe pas
                                $messageAjout="Cette catégorie n'existe pas!";
                            }

                        }catch(EXCEPTION $error){
                            die($error->getMessage());
                        }     

                    }else{
                        //Message durée ne peut être inf. ou égal à 0
                        $messageAjout = "La durée ne peut être inférieure ou égale à 0!";                        
                    }

                }else{
                    //Message joueurs maximum sup. ou égal joueurs minimum
                    $messageAjout = "Le nombre de joueurs max. doit être supérieur ou égal au nombre de joueurs min.!";
                }
                
            } else {
                //Message joueurs minimum sup. ou égal à 1
                $messageAjout = "Le nombre de joueurs min. doit être supérieur ou égal à 1!";                
            }

        }else{
            //Message joueurs, durée categorie
            $messageAjout = "Vérifiez le format des champs: joueurs, durée et/ou catégorie!";
        }
        
    }else{
        //Message tous les champs ne sont pas remplis
        $messageAjout = "Veuillez remplir tous les champs !";
    }
}




include('includes/header.php');
?>

    <main>
        <section>
            <h1>Ajouter un jeu</h1>
            <form action="" method="post">
                <label for="titre">Titre :</label>
                <input type="text" name="titre" id="titre"
                    value="<?php if (array_key_exists('titre', $_POST)) {echo htmlspecialchars($_POST['titre']);}?>">
                <label for="joueursMin">Nombres de joueurs minimum :</label>
                <input type="number" name="joueursMin" id="joueursMin"
                    value="<?php if (array_key_exists('joueursMin', $_POST)) {echo htmlspecialchars($_POST['joueursMin']);}?>">
                <label for="joueursMax">Nombres de joueurs maximum :</label>
                <input type="number" name="joueursMax" id="joueursMax"
                    value="<?php if (array_key_exists('joueursMax', $_POST)) {echo htmlspecialchars($_POST['joueursMax']);}?>">
                <label for="duree">Durée d'une partie en minutes :</label>
                <input type="number" name="duree" id="duree"
                    value="<?php if (array_key_exists('duree', $_POST)) {echo htmlspecialchars($_POST['duree']);}?>">
                <label for="categorie">Catégorie :</label>
                <select name="categorie" id="categorie">
                    <optgroup label="Au choix :">
                        <?php echo($optionsCategorie) ?>
                    </optgroup>
                </select>
                <input type="submit" name="submitAjout">
            </form>
            <p>
                <?php echo($messageAjout) ?>
            </p>
        </section>
    </main>

<?php
    //J'inclus le footer à ma page
    include('includes/footer.php');
?>
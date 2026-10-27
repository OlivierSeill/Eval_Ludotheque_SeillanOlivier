<?php
//Démarrage de la session
session_start();

//Je mets en place ma variable d'affichage
$messageInscription = '';

//Inscription d'un nouveau membre
if(isset($_POST['submitInscription'])){

    //Vérification de la complétion de tout le formulaire
    if(!empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['email']) && !empty($_POST['password'])){
        
        //Vérification du format des données
        //L'email :
        if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){

            //Le mot de passe
            //On vérifie qu'il est de la bonne longueur(12 minimum)
            if(mb_strlen($_POST['password'],'UTF-8')>= 12){
                
                //Nettoyage des données à l'exception du mot de passe
                $prenom = trim($_POST['prenom']);
                $nom = trim($_POST['nom']);
                $email = trim($_POST['email']);

                //Hashage du mot de passe
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                //Utilisation des données pour l'enregistrement en base de  données
                try{
                    $pdo = new PDO('mysql:host=localhost;dbname=ludotheque','root','',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

                    //Je dois vérifier que l'eamil n'est pas déjà utilisé avant d'inscrire un nouveau membre
                    $request = $pdo->prepare('SELECT m.prenom, m.nom, m.email FROM membre m WHERE m.email = ?');

                    $request->bindParam(1,$email,PDO::PARAM_STR);

                    $request->execute();

                    $data = $request->fetch(PDO::FETCH_ASSOC);

                    //Si le tableau est vide je peux rajouter le membre sinon j'envoie le message d'erreur
                    if(empty($data)){
                        //Requête pour rajouter un nouveau membre
                        $request = $pdo->prepare('INSERT INTO membre (prenom, nom, email, mot_de_passe) VALUES(?, ?, ?, ?)');

                        $request->bindParam(1,$prenom,PDO::PARAM_STR);
                        $request->bindParam(2,$nom,PDO::PARAM_STR);
                        $request->bindParam(3,$email,PDO::PARAM_STR);
                        $request->bindParam(4,$password,PDO::PARAM_STR);

                        $request->execute();

                        //Message de confirmation d'inscription
                        $messageInscription = "L'utilisateur {$prenom} {$nom} - {$email}, a bien été inscrit.";

                    }else{
                        // Message d'erreur si l'email existe déjà dans la BDD
                        $messageInscription = "Cet email est déjà utilisé.";
                    }

                }catch(EXCEPTION $error){
                    die($error->getMessage());
                }

            }else{
                // Message d'erreur sur le format du mot de passe
                $messageInscription = "Le mot de passe doit être de 12 caractères minimum.";
            }

        }else{
            // Message d'erreur sur le format de l'email
            $messageInscription = "L'email saisi n'est pas au bon format.";
        }

    }else{
        // Message d'erreur si l'un des champs n'est pas remplis
        $messageInscription = "Veuillez remplir tous les champs du formulaire.";
    }

}

//J'inclus le header à ma page
include('includes/header.php');
?>


    <main>
            <section>
                <h2>Inscription</h2>
                <form action="" method="post">
                    <label for="prenom">Prenom :</label>
                    <input type="text" name="prenom" id="prenom">
                    <label for="nom">Nom <input type="text" name="nom" id="nom"></label>
                    <label for="email">Email :</label>
                    <input type="text" name="email" id="email">
                    <label for="password">Mot de Passe :</label>
                    <input type="password" name="password" id="password">
                    <input type="submit" name="submitInscription">
                </form>
                <p>
                    <?php echo($messageInscription) ?>
                </p>
            </section>
    </main>

<?php
    //J'inclus le footer à ma page
    include('includes/footer.php');
?>
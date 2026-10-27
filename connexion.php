<?php
//Démarrage de la session
session_start();

//Je mets en place ma variable d'affichage
$messageConnexion = '';

//Connexion d'un membre
if(isset($_POST['submitConnexion'])){

    //Vérification de la complétion de tout le formulaire
    if(!empty($_POST['email']) && !empty($_POST['password'])){
        //Vérification du format des données
        //L'email :
        if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
            //Nettoyage des données à l'exception du mot de passe
            $email = trim($_POST['email']);

            //Vérification que le membre est bien inscrit
            try{
                $pdo = new PDO('mysql:host=localhost;dbname=ludotheque','root','',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

                $request = $pdo->prepare('SELECT m.id_membre, m.prenom, m.nom, m.email, m.mot_de_passe, m.date_inscription FROM membre m WHERE m.email = ?');

                $request->bindParam(1,$email,PDO::PARAM_STR);

                $request->execute();

                $data = $request->fetch(PDO::FETCH_ASSOC);

                //Si les données ne sont pas vides, le membre existe, je procède à la vérif du mot de passe
                if(!empty($data)){
                    if(password_verify($_POST['password'],$data["mot_de_passe"])){
                        //Si les mots de passe correspondent je procède à la connexion
                        $_SESSION['id'] = $data['id_membre'];
                        $_SESSION['prenom'] = $data['prenom'];
                        $_SESSION['nom'] = $data['nom'];
                        $_SESSION['email'] = $data['email'];

                        //Et je redirige vers la page index
                        header("Location:index.php");


                    }else{
                        //Message d'erreur si le mot de passe ne correspond pas
                        $messageConnexion = "Le Mot de Passe est incorrect.";
                    }

                }else{
                    //Message si l'email n'est pas connu
                    $messageConnexion = "Cet Email n'est pas connu.";
                }

            }catch(EXCEPTION $error){
                die($error->getMessage());
            }
        }else{
            //Message d'erreur
            $messageConnexion = "L'email saisi n'est pas au bon format.";
        }
    }else{
        // Message d'erreur
        $messageConnexion = 'Veuillez remplir tous les champs du formulaire.';
    }
}

//J'inclus le header à ma page
include('includes/header.php');
?>


    <main>
        <section>
            <h2>Connexion</h2>
            <form action="" method="post">
                <label for="email">Email :</label>
                <input type="text" name="email" id="email">
                <label for="password">Mot de Passe :</label>
                <input type="password" name="password" id="password">
                <input type="submit" name="submitConnexion">
            </form>
            <p>
                <?php echo($messageConnexion) ?>
            </p>
        </section>
    </main>

<?php
    //J'inclus le footer à ma page
    include('includes/footer.php');
?>
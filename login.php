<?php 

ob_start();
include "partials/header.php";
//include "config/db.php"

// 1) On vérifie que le form ait été soumis avec POST et que le bouton de submit ait été cliqué
if (($_SERVER["REQUEST_METHOD"] === "POST") && (isset($_POST["submit"]))) {

    // 2) On vérifie que tous les champs soient remplis
    if (!empty($_POST["email"]) || !empty($_POST["password"])) {

        // 3) On vient vérifier que le mail soit au bon format
        if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {

            $email = $_POST["email"];

            // 4) On va chercher en BDD le user qui correspond à l'email (requete préparée) +
            // Si on trouve personne on affiche un message d'erreur 
            $sql = "SELECT * FROM users WHERE email = ?";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            if ($user) {

                // Si on trouve bien une personne on vient vérifier son mot de passe (voir la fonction password_verify)
                $password = $_POST["password"];
                $hash = $user["password_hash"];

                // On vient comparer le mdp donné par le user avec celui de la BDD (password_hash)
                if (password_verify($password, $hash)) {

                    // Pour connecter le user et démarrer une session on va utiliser session_start()
                    session_start(); 

                    // J'inclus dans la suprglobale $_SESSION, les infos du user que je récupère de la BDD
                    $_SESSION = $user;

                     // 5) Si le mdp est bon, on redirige vers la homepage (On redirige avec Header("Location: ma-page.php"))
                    Header("Location: index.php");

                    ob_flush();

                } else {
                    $error = "Mot de passe incorrect";
                }
            } else {
                $error = "Désolé votre compte n'existe pas";
            }
        } else {
            $error = "Attention le mail n'est pas au bon format";
        }
    } else {
        $error = "Veuillez remplir tous les champs";
    }
}

?>


<form action="../actions/connexion.php" method="POST">
    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Password :</label>
    <input type="password" id="password" name="password" required>

    <a href="./profile.php"><button type="submit">Se connecter</button></a>
</form>

<p>Pas encore inscrit ? <a href="signup.php">Inscrivez-vous </a>.</p>


<?php 

include "partials/footer.php";

?>
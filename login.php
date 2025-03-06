<?php 

ob_start();
include "partials/header.php";
//include "config/db.php"

if (($_SERVER["REQUEST_METHOD"] === "POST") && (isset($_POST["submit"]))) {

    
    if (!empty($_POST["email"]) || !empty($_POST["password"])) {

        
        if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {

            $email = $_POST["email"];

            
            $sql = "SELECT * FROM users WHERE email = ?";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            if ($user) {

                $password = $_POST["password"];
                $hash = $user["password_hash"];

                if (password_verify($password, $hash)) {

                    session_start(); 

                    $_SESSION = $user;

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

    <button type="submit">Se connecter</button>
</form>

<p>Pas encore inscrit ? <a href="signup.php">Inscrivez-vous </a>.</p>


<?php 

include "partials/footer.php";

?>
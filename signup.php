<?php 

include "partials/header.php";
//include "config/db.php"

// On vérifie que la méthode est bien POST et que le form ait bien été soumis
if (($_SERVER["REQUEST_METHOD"] === "POST") && (isset($_POST["submit"]))) {

    // On vérifie que les champs ne soient pas vide 
    if (!empty($_POST["username"]) || !empty($_POST["email"]) || !empty($_POST["password"]) || !empty($_POST["confirm"])) {

        // Vérification de l'email 
        if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        
            $username = htmlspecialchars($_POST["username"]);
            $email = $_POST["email"];
            $password = $_POST["password"];
            $confirm = $_POST["confirm"];

            // Je viens vérifier que les mots de passe soient les memes
            if ($password === $confirm) {

                // Je peux désormais vérifier que le user n'existe dèjà pas en BDD, notamment via son email
                // On vérifie également que le username ne soit pas déjà utilisé
                $sql = "SELECT * FROM users WHERE email = ? OR username = ?";

                // Les 3 étapes afin d'éxecuter une requete préparée à l'aide de $pdo (qui est dans notre fichier db.php)
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$email, $username]);
                $user = $stmt->fetch();  

                // Si on ne trouve personne alors on peut poursuivre et enregistrer le nouveau user en BDD
                if (!$user) {

                    // On va hasher (créér une empreinte cryptographique) le mot de passe avant d'ajouter le user en BDD
                    $hash = password_hash($password, PASSWORD_DEFAULT); // Hache le password avec bcrypt (gère le sel automatiquement)
                
                    $sql = "INSERT INTO users(username, email, password_hash) VALUES(?, ?, ?)";

                    // On tente d'insérer un user dans un try et si tout se passe bien on affiche un message   
                    try {
                        // Les 3 étapes afin d'éxecuter une requete préparée à l'aide de $pdo (qui est dans notre fichier db.php)
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$username, $email, $hash]);

                        echo "Utilisateur $username ajouté avec succès !";

                        // Si il y a un souci on affiche l'erreur en question
                    } catch(PDOException $error) {

                        echo "Erreur : $error";

                    }
                
                    // Si on trouve le username ou l'email en BDD alors on affiche une erreur 
                } else if ($user && $user["username"] === $username) {

                    $error = "Username déjà pris";

                } else if ($user && $user["email"] === $email) {

                    $error = "Email déjà pris";
                }

            } else {
                $error = "Les mots de passe doivent etre similaires";

            }

        } else {

            $error = "Votre email n'est pas au bon format";

        }
    } else {
        // On affiche l'erreur si un des champs n'est pas rempli 
        $error = "Veuillez remplir tous les champs";
    }
    
}

?>

<?php include "partials/header.php"; ?>

<form action="../actions/inscription.php" method="POST">
    <label for="username">Nom :</label>
    <input type="text" id="username" name="username" required>

    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required>

    <label for="comfrim">Confirmez le mot de passe :</label>
    <input type="password" id="comfirm" name="comfirm" required>

    <a href="./profile.php"><button type="submit">S'inscrire</button></a>
</form>

<p>Déjà inscrit ? <a href="./login.php">Connectez-vous</a>.</p>

<?php 

include "partials/footer.php";

?>

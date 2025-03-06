<?php 

include "partials/header.php";
//include "config/db.php"

if (($_SERVER["REQUEST_METHOD"] === "POST") && (isset($_POST["submit"]))) {

    if (!empty($_POST["username"]) || !empty($_POST["email"]) || !empty($_POST["password"]) || !empty($_POST["confirm"])) {

        if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        
            $username = htmlspecialchars($_POST["username"]);
            $email = $_POST["email"];
            $password = $_POST["password"];
            $confirm = $_POST["confirm"];

            if ($password === $confirm) {

                $sql = "SELECT * FROM users WHERE email = ? OR username = ?";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([$email, $username]);
                $user = $stmt->fetch();  

                if (!$user) {

                    
                    $hash = password_hash($password, PASSWORD_DEFAULT); 
                    $sql = "INSERT INTO users(username, email, password_hash) VALUES(?, ?, ?)";

                    // On tente d'insérer un user dans un try et si tout se passe bien on affiche un message   
                    try {
                        // Les 3 étapes afin d'éxecuter une requete préparée à l'aide de $pdo (qui est dans notre fichier db.php)
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$username, $email, $hash]);
                        echo "Utilisateur $username ajouté avec succès !";
                    } catch(PDOException $error) {
                        echo "Erreur : $error";
                    }
                
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

    <button type="submit">S'inscrire</button>
</form>

<p>Déjà inscrit ? <a href="./login.php">Connectez-vous</a>.</p>

<?php 

include "partials/footer.php";

?>

<?php
require_once __DIR__ . '/../db/login.php';

class Users {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function signup($username, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $email, $hashed_password]);
    }

    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($passeword, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function modifierUsers($id, $username, $email) {
        $stmt = $this->pdo->prepare("UPDATE user SET username = ?,  = ? WHERE id = ?");
        return $stmt->execute([$username, $email, $id]);
    }
}
?>

<?php
require_once __DIR__ . '/../db/login.php';

class Livres {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function ajouterLivred($users_id, $title, $author, $picture) {
        $stmt = $this->pdo->prepare("INSERT INTO livres (users_id, title, author, picture) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$users_id, $title, $author, $picture]);
    }

    public function supprimerLivres($id) {
        $stmt = $this->pdo->prepare("DELETE FROM livres WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getLivresUsers($users_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM livres WHERE users_id = ?");
        $stmt->execute([$users_id]);
        return $stmt->fetchAll();
    }
}

function getBooksByGenre($genre) {
    $url = "https://www.googleapis.com/books/v1/volumes?q=subject:$genre&langRestrict=fr";
    $response = file_get_contents($url);
    return json_decode($response, true);
}

function getBooksByTitle($title) {
    $url = "https://www.googleapis.com/books/v1/volumes?q=intitle:$title&langRestrict=fr";
    $response = file_get_contents($url);
    return json_decode($response, true);
}
?>


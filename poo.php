<?php
class Utilisateur {
    private $id;
    private $nom;
    private $email;
    private $motDePasse;

    // Constructeur
    public function __construct($nom, $email, $motDePasse) {
        $this->nom = $nom;
        $this->email = $email;
        $this->motDePasse = password_hash($motDePasse, PASSWORD_BCRYPT); // Chiffrement du mot de passe
    }

    // Méthode pour enregistrer un utilisateur dans la base de données
    public function inscrire($connexion) {
        $stmt = $connexion->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $this->nom, $this->email, $this->motDePasse);
        return $stmt->execute();
    }

    // Méthode pour vérifier les informations de connexion
    public static function connecter($connexion, $email, $motDePasse) {
        $stmt = $connexion->prepare("SELECT id, nom, mot_de_passe FROM utilisateurs WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $utilisateur = $result->fetch_assoc();
            if (password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
                return $utilisateur; // Retourne les informations de l'utilisateur
            }
        }
        return false; // Échec de la connexion
    }

    // Méthode pour mettre à jour les informations de l'utilisateur
    public function mettreAJour($connexion, $nouveauNom, $nouveauEmail) {
        $stmt = $connexion->prepare("UPDATE utilisateurs SET nom = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $nouveauNom, $nouveauEmail, $this->id);
        return $stmt->execute();
    }
}
?>
<?php
class Livre {
    private $id;
    private $titre;
    private $auteur;
    private $datePublication;
    private $imageUrl;

    // Constructeur
    public function __construct($id, $titre, $auteur, $datePublication, $imageUrl) {
        $this->id = $id;
        $this->titre = $titre;
        $this->auteur = $auteur;
        $this->datePublication = $datePublication;
        $this->imageUrl = $imageUrl;
    }

    // Méthode pour ajouter un livre à la bibliothèque personnelle
    public static function ajouterALaBibliotheque($connexion, $utilisateurId, $livreId) {
        $stmt = $connexion->prepare("INSERT INTO bibliotheque (utilisateur_id, livre_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $utilisateurId, $livreId);
        return $stmt->execute();
    }

    // Méthode pour supprimer un livre de la bibliothèque personnelle
    public static function supprimerDeLaBibliotheque($connexion, $utilisateurId, $livreId) {
        $stmt = $connexion->prepare("DELETE FROM bibliotheque WHERE utilisateur_id = ? AND livre_id = ?");
        $stmt->bind_param("ii", $utilisateurId, $livreId);
        return $stmt->execute();
    }

    // Méthode pour récupérer les livres de la bibliothèque personnelle
    public static function recupererBibliotheque($connexion, $utilisateurId) {
        $stmt = $connexion->prepare("SELECT livres.* FROM livres 
                                    JOIN bibliotheque ON livres.id = bibliotheque.livre_id 
                                    WHERE bibliotheque.utilisateur_id = ?");
        $stmt->bind_param("i", $utilisateurId);
        $stmt->execute();
        $result = $stmt->get_result();
        $livres = [];

        while ($row = $result->fetch_assoc()) {
            $livres[] = new Livre($row['id'], $row['titre'], $row['auteur'], $row['date_publication'], $row['image_url']);
        }

        return $livres;
    }
}
?>
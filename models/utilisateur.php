<?php 

/**
 * Utilisateur inscrit au site
 */
class Utilisateur extends Db {

    protected static string $db_table = "utilisateur";

    protected static array $ignored_columns = ["password"];

    /**
     * Identifiant unique de l'utilisateur
     *
     * @var int|null
     */
    public ?int $id;

    /**
     * Pseudonyme de l'utilisateur
     *
     * @var string|null
     */
    public ?string $pseudo;
    
    /**
     * Mot de passe de l'utilisateur
     *
     * @var string|null
     */
    public ?string $password;

    /**
     * Adresse e-mail de l'utilisateur
     *
     * @var string|null
     */
    public ?string $email;

    /**
     * Date d'inscription de l'utilisateur (au format YYYY-MM-DD HH:MM:SS)
     * 
     * @var string|null
     */
    public ?string $date_inscription;

    /**
     * Indique si l'utilisateur a des droits d'accès administrateur
     * 
     * @var boolean
     */
    public bool $is_admin;

    /**
     * Partie dans laquelle cet utilisateur est en train de jouer
     * TODO: créer une fonction permettant de switcher de partie (actuelle c'est défini à la connexion)
     *
     * @var integer|null
     */
    public ?int $partie_id = null;

    /**
     * Change le mot de passe de l'utilisateur
     *
     * @param string $password Nouveau mot de passe de l'utilisateur
     * @return void
     */
    public function setMotDePasse(string $password): void {
        if(empty($password)) {
            $this->password = "";
            return;
        }
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Connexion d'un utilisateur
     *
     * @param string $login
     * @param string $password
     * @return bool True si les infos de connexion sont correctes, false sinon
     */
    public static function connexion(string $login, string $password): bool {
        // Première requête pour vérifier le mot de passe
        $query = "SELECT password FROM utilisateur WHERE `login` = ?";
        $params = [$login];
        $result = self::fetch($query, $params);

        if(count($result) > 0 && password_verify($password, $result[0]["password"])) {
            // Deuxième requête pour récupérer les infos en cas de connexion réussie
            $query = "SELECT id, pseudo, is_admin, partie_id FROM utilisateur WHERE login = ?";
            $params = [$login];
            $result = self::fetch($query, $params, "Utilisateur");
            $utilisateur = $result[0];
            $_SESSION["utilisateur_id"] = $utilisateur->id;
            $_SESSION["utilisateur_pseudo"] = $utilisateur->pseudo;
            $_SESSION["utilisateur_admin"] = $utilisateur->is_admin;
            $_SESSION["partie_id"] = $utilisateur->partie_id;
            return true;
        }
        return false;
    }

    /**
     * Déconnexion de l'utilisateur
     *
     * @return void
     */
    public static function deconnexion(): void {
        session_unset();
        session_destroy();
        session_start();
    }

    /**
     * Vérifie si l'utilisateur est connecté
     *
     * @param boolean $admin Des droits d'accès administrateur sont-ils requis ?
     * @return boolean True si l'utilisateur est connecté, false sinon
     */
    public static function estConnecte(bool $admin = false): bool {
        if(isset($_SESSION["utilisateur_id"]) && $_SESSION["utilisateur_id"] > 0) {
            if($admin && !$_SESSION["utilisateur_admin"]) {
                return false;
            }
            // Si l'utilisateur est dans une partie, on la définit dans Partie::$current_partie
            if(isset($_SESSION["partie_id"]) && $_SESSION["partie_id"] != null) {
                Partie::$current = Partie::selectById($_SESSION["partie_id"]);
            }
            return true;
        }
        return false;
    }

    /**
     * Insère une nouvelle entrée dans la base de données pour enregistrer cet objet
     * On surcharge la méthode de la classe parent Db pour gérer le cas particulier du mot de passe.
     *
     * @return void
     */
    public function insert(): void {
        // On insère d'abord l'utilisateur sans le mot de passe (colonne ignorée dans $ignored_columns)
        parent::insert();

        // On insère ensuite le mot de passe haché
        $sql = "UPDATE " . self::$db_table . " SET password = ? WHERE id = ?";
        $params = [$this->password, $this->id];
        $this->query($sql, $params);
    }

    /**
     * Met à jour cet objet dans la base de données.
     * On surcharge la méthode de la classe parent Db pour gérer le cas particulier du mot de passe.
     *
     * @return void
     */
    public function update(): void {
        parent::update(); // Appel de la méthode update de la classe parent
        
        if($this->password != "") {
            // Changement de mot de passe
            $sql = "UPDATE " . self::$db_table . " SET password = ? WHERE id = ?";
            $params = [$this->password, $this->id];
            $this->query($sql, $params);
        }
    }
}
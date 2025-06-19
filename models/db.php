<?php 

/**
 * Classe de base pour les interactions avec la base de données, dont doit hériter chaque classe stockée en BdD.
 */
class Db {
    /**
     * Connexion PDO à la base de données, partagée par toutes les instances de la classe
     *
     * @var PDO|null
     */
    protected static ?PDO $pdo = null;

    /**
     * Stocke le nom de la table qui correspond à cette classe
     * Cette propriété doit être accédée en "Late Static Binding" pour récupérer la valeur des classes enfant avec la syntaxe suivante : static::$db_table
     *
     * @var string
     */
    protected static string $db_table = "";

    /**
     * Stocke le nom des propriétés de la classe qui ne correspondent pas à des colonnes de la base de données et qui doivent donc être ignorées lors du CRUD. 
     * Cette propriété doit être accédée en "Late Static Binding" pour récupérer la valeur des classes enfant avec la syntaxe suivante : static::$db_columns
     *
     * @var ?array
     */
    protected static array $ignored_columns = [];

    /**
     * Identifiant unique dans la base de données.
     *
     * @var integer|null
     */
    public ?int $id;

    public function __construct() {
        if(self::$pdo == null) {
            $dsn = "mysql:dbname=" . DB_NAME .";host=" . DB_HOST . ";charset=utf8mb4";
            try {
                self::$pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Affichage des erreurs de PDO
            }
            catch(Exception $e) {
                echo "Erreur de connexion à la base de données : " . $e->getMessage();
                exit;
            }
        }
    }

    /**
     * Exécute une requête préparée sans lecture de données
     *
     * @param string $query Requête SQL
     * @param array $values
     * @return PDOStatement PDO result set obtenu après exécution de la requête
     */
    protected function query(string $query, array $values): PDOStatement {

        $stmt = self::$pdo->prepare($query);
        $success = $stmt->execute($values);

        if(!$success) {
            echo "Erreur dans l'exécution de la requête : <br>";
            echo $query;
            var_dump($values);
            var_dump(self::$pdo->errorInfo());
            die;
        }

        return $stmt;
    }

    /**
     * Exécute une requête préparée et renvoie les données récupérées
     *
     * @param string $query Requête SQL
     * @param array $values
     * @param string|null $class_name Nom de la classe correspondant aux données (null si aucune)
     * @return array Tableau des résultats contenant des objets si $class_name a été spécifié, des tableaux associatifs sinon
     */
    protected static function fetch(string $query, array $values, ?string $class_name = null): array {

        if(self::$pdo == null) {
            // PDO n'a pas encore été initialisé, on crée une instance de la classe pour l'initialiser
            $db = new Db();
        }

        $stmt = self::$pdo->prepare($query);
        $success = $stmt->execute($values);

        if(!$success) {
            echo "Erreur dans l'exécution de la requête : <br>";
            echo $query;
            var_dump($values);
            var_dump(self::$pdo->errorInfo());
            die;
        }

        if($class_name != null) {
            // Fetch class
            $results = $stmt->fetchAll(PDO::FETCH_CLASS, $class_name);
            return $results;
        }
        else {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }
    }


    /**
     * Insère une nouvelle entrée dans la base de données pour enregistrer cet objet
     *
     * @return void
     */
    public function insert(): void {
        
        $query = "INSERT INTO " . static::$db_table . " (";
        $query_values = "";

        // Liste des colonnes : on récupère les propriétés de l'objet
        $columns = array_keys(get_object_vars($this));

        $i = 0;
        foreach($columns as $column) {
            // Skip la colonne "id"
            if($column == "id")
                continue;
            // Skip si la colonne fait partie de celles ignorées
            if(in_array($column, static::$ignored_columns))
                continue;

            // Ajout d'une virgule (sauf sur la première itération)
            if($i != 0) {
                $query .= ", ";
                $query_values .= ", ";
            }
                
            $query .= $column;
            $query_values .= "?";
            $value = $this->$column;
            if($value === false)
                $value = 0; // Conversion de false en 0
            $values[] = $value;
            $i ++;
        }

        $query .= ") VALUES (" . $query_values . ")";
        // var_dump($query);

        $stmt = self::$pdo->prepare($query);
        $success = $stmt->execute($values);

        if(!$success) {
            echo "Erreur dans l'exécution de la requête : <br>";
            echo $query;
            var_dump(self::$pdo->errorInfo());
            die;
        }

        $this->id = self::$pdo->lastInsertId();
    }

    /**
     * Met à jour cet objet dans la base de données
     *
     * @return void
     */
    public function update(): void {

        if(!isset($this->id) || $this->id == null)
            return;

        $query = "UPDATE " . static::$db_table . " SET ";
        $values = [];

        // Liste des colonnes : on récupère les propriétés de l'objet
        $columns = array_keys(get_object_vars($this));

        $i = 0;
        foreach($columns as $column) {
            // Skip la colonne "id"
            if($column == "id")
                continue;
            // Skip si la colonne fait partie de celles ignorées
            if(in_array($column, static::$ignored_columns))
                continue;

            // Ajout d'une virgule (sauf sur la première itération)
            if($i != 0) {
                $query .= ", ";
            }
            $query .= $column . " = ?";
            $value = $this->$column;
            if($value === false)
                $value = 0; // Conversion de false en 0
            $values[] = $value;
        }

        $query .= " WHERE id = ?";
        $values[] = $this->id;

        $stmt = self::$pdo->prepare($query);
        $stmt->execute($values);
    }

    /**
     * Sélectionne une entrée de la base de données en fonction de son id
     *
     * @param integer $id
     * @return Object|false Objet de la classe instanciée si une occurence a été trouvée, false sinon
     */
    public static function selectById(int $id): Object|false {

        // Récupération du nom de la table de la classe enfant en late static binding avec static::
        $query = "SELECT * FROM " . static::$db_table . " WHERE id = ?";

        $results = self::fetch($query, [$id], static::$db_table);

        if(count($results) > 0) {
            return $results[0];
        }
        return false;
    }

    /**
     * Récupère toutes les entrées de la table associée à cette classe, ou une partie de celles-ci si un offset et/ou un limit sont spécifiés.
     *
     * @param ?int $limit Nombre d'entrées à récupérer (null pour toutes)
     * @param ?int $offset Décalage à partir duquel récupérer les entrées (null pour commencer au début)
     * @return array Tableau d'objets de cette classe
     */
    public static function selectAll(?int $limit = null, ?int $offset = null): array {

        $query = "SELECT * FROM " . static::$db_table;
        // Cas particulier où l'on ne peut pas passer les arguments de LIMIT et OFFSET dans notre requête préparée générique car ils seraient castés en string. Comme on est sûrs que c'est des int et qu'il n'y a donc pas de risque d'injection SQL, on les met directement dans la requête
        if($limit !== null && is_int($limit)) {
            $query .= " LIMIT " . $limit;
            if($offset !== null && is_int($offset)) {
                $query .= " OFFSET " . $offset;
            }
        }
        else {
            if($offset !== null && is_int($offset)) {
                $query .= " LIMIT -1 OFFSET " . $offset;
            }
        }

        // DEBUG : ça va fonctionner avec self::fetch() ? C'est une idée de Copilot
        $results = self::fetch($query, [], static::$db_table);

        return $results;
    }

    public function delete(): bool {

        $query = "DELETE FROM " . static::$db_table . " WHERE id = ?";

        $stmt = $this->query($query, [$this->id]);

        if($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Retourne le nombre d'entrées dans la table associée à cette classe
     *
     * @return integer
     */
    public static function count(): int {

        $query = "SELECT COUNT(*) AS nb FROM " . static::$db_table;

        $results = self::fetch($query, []);
        return (int)$results[0]["nb"];

    }
}
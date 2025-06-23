<?php 

/**
 * Clan lycéen au sein d'une partie
 */
class Clan extends Db {

    protected static string $db_table = "clan";

    protected static array $ignored_columns = [];
    
    /**
     * Utilisateur qui joue ce clan
     *
     * @var integer
     */
    public int $utilisateur_id;
    
    /**
     * Partie où est jouée ce clan
     *
     * @var integer
     */
    public int $partie_id;

    /**
     * Nom du clan
     *
     * @var string
     */
    public string $nom;

    /**
     * Couleur du clan (en hexadécimal)
     *
     * @var string
     */
    public string $couleur;

    /**
     * Idéologie du clan
     *
     * @var Ideologie
     */
    public Ideologie $ideologie;

    /**
     * Nombre de troupes dans ce clan
     * 
     * @var integer
     */
    public int $nb_troupes = 0;

    /**
     * Liste des troupes en réserve de ce clan
     * Une troupes_liste est un tableau associatif dont les clés sont les id des unités et les valeurs sont les objets Troupe
     * 
     * @var array<int, Troupe>
     */
    private array $troupes_liste = [];

    /* --- Ressources --- */
    public int $argent = 0;
    public int $culture = 0;
    public int $savoir = 0;
    public int $web = 0;

    /* --- Technologies --- */
    public int $tech_atk = 0;
    public int $tech_def = 0;
    public int $tech_argent = 0;
    public int $tech_culture = 0;
    public int $tech_savoir = 0;
    public int $tech_web = 0;
    public int $tech_hierarchie = 0;
    public int $tech_diplomatie = 0;

    public function __construct() {
        parent::__construct();
        
        // On initialise la liste des troupes en réserve du clan
        $unites = Unite::getListeClan();
        foreach($unites as $unite) {
            $this->troupes_liste[$unite->id] = new Troupe();
            $this->troupes_liste[$unite->id]->unite_id = $unite->id;
            $this->troupes_liste[$unite->id]->insert(); // On insère une nouvelle troupes vide pour chaque unité
        }
    }
    
    /**
     * Récupère la liste des troupes en réserve du clan
     * 
     * @return array<int, Troupe> Tableau associatif dont les clés sont les id des unités et les valeurs sont les objets Troupe
     */
    public function getTroupesListe(): array {
        if(empty($this->troupes_liste)) {
            $troupes = Troupe::selectReserveClan($this->id);
            // On remplit la liste des troupes en réserve du clan en fonction des id des unités
            foreach($troupes as $troupe) {
                $this->troupes_liste[$troupe->unite_id] = $troupe;
            }
        }
        return $this->troupes_liste;
    }
}
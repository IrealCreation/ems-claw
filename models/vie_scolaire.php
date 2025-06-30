<?php 

/**
 * Clan lycéen au sein d'une partie
 */
class VieScolaire extends Db {

    protected static string $db_table = "vie_scolaire";

    protected static array $ignored_columns = ["troupes_liste", "salles"];
    
    /**
     * Utilisateur qui joue cette vie scolaire
     *
     * @var integer
     */
    public int $utilisateur_id;
    
    /**
     * Partie où est jouée cette vie scolaire
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
    
    // TODO: bonus de départ

    /**
     * Nombre de troupes dans cette vie scolaire
     * 
     * @var integer
     */
    public int $nb_troupes = 0;

    /**
     * Liste des troupes en réserve de cette vie scolaire
     * Une troupes_liste est un tableau associatif dont les clés sont les id des unités et les valeurs sont les objets Troupe
     * 
     * @var array<int, Troupe>
     */
    private array $troupes_liste = [];

    /**
     * Salles contrôlées par cette vie scolaire
     *
     * @var array
     */
    private array $salles = [];

    /* --- Ressources --- */
    public int $argent = 0;
    public int $pacification = 0;
    public int $web = 0;

    /* --- Technologies --- */
    public int $tech_atk = 0;
    public int $tech_def = 0;
    public int $tech_argent = 0;
    public int $tech_pacification = 0;
    public int $tech_web = 0;
    public int $tech_hierarchie = 0;
    public int $tech_diplomatie = 0;

    public function insert(): void {
        parent::insert();
        
        // On initialise la liste des troupes en réserve du clan
        $unites = Unite::getListeVieScolaire();
        foreach($unites as $unite) {
            $this->troupes_liste[$unite->id] = new Troupe();
            $this->troupes_liste[$unite->id]->unite_id = $unite->id;
            $this->troupes_liste[$unite->id]->vie_scolaire_id = $this->id;
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
            $troupes = Troupe::selectReserveVieScolaire($this->id);
            // On remplit la liste des troupes en réserve du clan en fonction des id des unités
            foreach($troupes as $troupe) {
                $this->troupes_liste[$troupe->unite_id] = $troupe;
            }
        }
        return $this->troupes_liste;
    }

    /**
     * Renvoie les salles contrôlées par cette vie scolaire (clan_id null)
     *
     * @return array<Salle>
     */
    public function getSalles(): array {
        if(empty($this->salles)) {
            $salles = Partie::$current->getSalles();
            foreach($salles as $salle) {
                if($salle->clan_id == null) {
                    $this->salles[] = $salle;
                }
            }
        }
        return $this->salles;
    }
}
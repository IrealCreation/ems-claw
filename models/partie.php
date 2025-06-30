<?php 

class Partie extends Db {

    protected static string $db_table = "partie";

    protected static array $ignored_columns = ["salles"];

    /**
     * Partie en cours dans la session de l'utilisateur connecté
     *
     * @var Partie
     */
    public static Partie $current;

    /**
     * Identifiant unique de la partie
     *
     * @var int|null
     */
    public ?int $id;

    /**
     * Nom du lycée
     *
     * @var string|null
     */
    public ?string $nom;

    /**
     * Taux de Sécurité Scolaire (TSS) de ce lycée
     *
     * @var float
     */
    public float $tss;

    /**
     * Date de création de la partie (au format YYYY-MM-DD HH:MM:SS)
     *
     * @var string|null
     */
    public ?string $date_creation;

    /**
     * Joueurs dans la partie
     *
     * @var array<Utilisateur>
     */
    public ?int $utilisateurs;

    /**
     * Salles du lycée
     *
     * @var array<Salle>
     */
    private array $salles = [];

    /**
     * Renvoie les salles de cette partie.
     *
     * @return array<Salle>
     */
    public function getSalles(): array {
        if(empty($this->salles)) {
            Salle::selectByPartieId($this->id);
        }
        return $this->salles;
    }

    /**
     * Renvoie les salles d'un clan spécifique dans cette partie.
     *
     * @param integer $clan_id
     * @return array<Salle>
     */
    public function getSallesClan(int $clan_id): array {
        $salles = [];
        foreach($this->getSalles() as $salle) {
            if($salle->clan_id === $clan_id) {
                $salles[] = $salle;
            }
        }
        return $salles;
    }

}
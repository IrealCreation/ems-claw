<?php 

/**
 * Salle du lycée au sein d'une partie, rapportant des ressources, contrôlée par un clan ou par la vie scolaire
 */
class Salle extends Db {

    protected static string $db_table = "salle";

    /**
     * Partie dont fait partie cette salle
     *
     * @var integer|null
     */
    public ?int $partie_id;

    /**
     * Id du clan contrôlant cette salle (null si elle est contrôlée par la vie scolaire)
     *
     * @var integer|null
     */
    public ?int $clan_id;

    /**
     * Nom de la salle
     *
     * @var string
     */
    public string $nom;

    /* --- Ressources de base rapportées par cette salle à chaque tour (sans modificateurs) --- */
    public int $argent = 0;
    public int $culture = 0;
    public int $savoir = 0;
    public int $web = 0;

    /* -- Aménagements de la salle -- */
    public bool $amenagement_defense = false;
    public bool $amenagement_argent = false;
    public bool $amenagement_culture = false;
    public bool $amenagement_savoir = false;
    public bool $amenagement_web = false;

    /**
     * Renvoie le gain d'argent de la salle, en tenant compte des aménagements éventuels.
     *
     * @return integer
     */
    public function getGainArgent(): int {
        return $this->argent + ($this->amenagement_argent ? 10 : 0);
    }

    /**
     * Renvoie le gain de culture de la salle, en tenant compte des aménagements éventuels.
     *
     * @return integer
     */
    public function getGainCulture(): int {
        return $this->culture + ($this->amenagement_culture ? 1 : 0);
    }
    /**
     * Renvoie le gain de savoir de la salle, en tenant compte des aménagements éventuels.
     *
     * @return integer
     */
    public function getGainSavoir(): int {
        return $this->savoir + ($this->amenagement_savoir ? 1 : 0);
    }

    /**
     * Renvoie le gain de web de la salle, en tenant compte des aménagements éventuels.
     *
     * @return integer
     */
    public function getGainWeb(): int {
        return $this->web + ($this->amenagement_web ? 1 : 0);
    }
}
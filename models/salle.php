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
}
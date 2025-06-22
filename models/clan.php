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

    /* --- Ressources --- */
    public int $argent = 0;
    public int $culture = 0;
    public int $savoir = 0;
    public int $web = 0;
}
<?php 

/**
 * Snapshot de l'état d'un clan au début d'un tour de jeu, effectué automatiquement à chaque début de tour et pouvant être récupéré par l'espionnage web.
 */
class ClanTour extends Db {

    protected static string $db_table = "clan_tour";

    /**
     * Id du clan dont est ce snapshot
     *
     * @var integer
     */
    public int $clan_id;

    /**
     * Tour de jeu auquel ce snapshot correspond
     *
     * @var integer
     */
    public int $tour;

    /**
     * Nombre de troupes dans ce clan
     * 
     * @var integer
     */
    public int $nb_troupes = 0;

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
}
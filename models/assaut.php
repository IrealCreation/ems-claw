<?php 

/**
 * Assaut d'un clan ou de la vie scolaire ciblant une salle contrôlée par un autre clan ou par la vie scolaire.
 * 
 * Fait également office de rapport de combat une fois l'assaut terminé.
 */
class Assaut extends Db {

    protected static string $db_table = "assaut";

    protected static array $ignored_columns = [];

    /**
     * Partie dans laquelle cet assaut a été effectué
     *
     * @var integer|null
     */
    public ?int $partie_id;

    /**
     * Id du clan étant l'assayant (null si c'est la vie scolaire)
     *
     * @var integer|null
     */
    public ?int $attaquant;

    /**
     * Id du clan qui se défend de cette assaut dans la salle ciblée (null si c'est la vie scolaire)
     *
     * @var integer|null
     */
    public ?int $defenseur = null;
    
    /**
     * Id de la salle ciblée par cet assaut
     *
     * @var integer|null
     */
    public ?int $salle_id = null;

    /**
     * Tour de jeu où est effectué cet assaut (en fin de tour)
     * 
     * @var integer
     */
    public int $tour;

    /**
     * L'assayant a-t-il remporté son assaut ? (null si l'assaut est en cours)
     * 
     * @var boolean|null
     */
    public ?bool $victoire;

}
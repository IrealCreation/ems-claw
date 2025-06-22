<?php 

/**
 * Représente une troupe d'une ou plusieurs unités identiques appartenant à un clan ou à la vie scolaire.
 * Peut être en garrison dans une salle ; faire partie d'un assaut ; ou en réserve.
 */
class Troupe extends Db {

    protected static string $db_table = "troupe";

    protected static array $ignored_columns = [];

    /**
     * Id du clan auquel appartient cette troupe (null si elle appartient à la vie scolaire)
     *
     * @var integer
     */
    public ?int $clan_id;

    /**
     * Id de la vie scolaire à laquelle appartient cette troupe (null si elle appartient à un clan)
     *
     * @var integer|null
     */
    public ?int $vie_scolaire_id = null;

    /**
     * Id de la salle où cette unité est en garrison (null si en réserve ou en assaut)
     *
     * @var integer|null
     */
    public ?int $salle_id;

    /**
     * Id de l'assaut dont fait partie cette unité (null si en garrison ou en réserve)
     *
     * @var integer|null
     */
    public ?int $assaut_id;

    /**
     * Type des unité constituant cette troupe
     *
     * @var string
     */
    public int $unite_id;

    /**
     * Nombre d'unités dans cette troupe
     *
     * @var integer
     */
    public int $nb = 0;

    /**
     * Nombre d'unités blessées dans cette troupe
     *
     * @var integer
     */
    public int $nb_blessees = 0;

    /**
     * Nombre d'unités fortifiées dans cette troupe (seulement si en garrison)
     * 
     * @var integer
     */
    public int $nb_fortifiees = 0;
}
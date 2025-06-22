<?php 

/**
 * Influence d'un clan à l'intérieur d'une salle.
 */
class Influence extends Db {

    protected static string $db_table = "influence";

    /**
     * Id du clan ayant cette influence (clé primaire composite)
     *
     * @var integer|null
     */
    public ?int $clan_id;

    /**
     * Id de la salle où cette influence est exercée (clé primaire composite)
     *
     * @var integer
     */
    public int $salle_id;

    /**
     * Niveau d'influence dans la salle (0 à 100)
     *
     * @var integer
     */
    public int $niveau = 0;
}
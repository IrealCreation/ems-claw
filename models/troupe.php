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
    public ?int $clan_id = null;

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
    public ?int $salle_id = null;

    /**
     * Id de l'assaut dont fait partie cette unité (null si en garrison ou en réserve)
     *
     * @var integer|null
     */
    public ?int $assaut_id = null;

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

    /**
     * Renvoie toutes les troupes en réserve d'un clan donné.
     *
     * @param integer $clan_id
     * @return array<Troupe>
     */
    public static function selectReserveClan(int $clan_id): array {
        $query = "SELECT * FROM " . static::$db_table . " WHERE clan_id = ? AND salle_id IS NULL AND assaut_id IS NULL";
        $params = [$clan_id];
        return self::fetch($query, $params, static::$db_table);
    }

    /**
     * Renvoie toutes les troupes en réserve de la vie scolaire.
     *
     * @return array<Troupe>
     */
    public static function selectReserveVieScolaire(int $vie_scolaire_id): array {
        $query = "SELECT * FROM " . static::$db_table . " WHERE vie_scolaire_id = ? AND salle_id IS NULL AND assaut_id IS NULL";
        $params = [$vie_scolaire_id];
        return self::fetch($query, $params, static::$db_table);
    }

    /**
     * Renvoie toutes les troupes en garrison dans une salle donnée.
     *
     * @param integer $salle_id
     * @return array<Troupe>
     */
    public static function selectSalle(int $salle_id): array {
        $query = "SELECT * FROM " . static::$db_table . " WHERE salle_id = ?";
        $params = [$salle_id];
        return self::fetch($query, $params, static::$db_table);
    }

    /**
     * Renvoie toutes les troupes dans un assaut donné.
     *
     * @param integer $assaut_id
     * @return array<Troupe>
     */
    public static function selectAssaut(int $assaut_id): array {
        // TODO: sélectionner toutes les troupes d'un assaut ciblant cette même salle ce même tour ?
        $query = "SELECT * FROM " . static::$db_table . " WHERE assaut_id = ?";
        $params = [$assaut_id];
        return self::fetch($query, $params, static::$db_table);
    }

    /**
     * Calcule la puissance totale d'une liste de troupes, sans prendre en compte la technologie et l'influence.
     *
     * @param array<Troupe> $troupes Liste de troupes
     * @param bool $atk True si on calcule la puissance d'attaque, false pour la puissance de défense
     * @return int Puissance totale des troupes
     */
    public static function puissance(array $troupes, bool $atk): int {
        $result = 0;
        foreach($troupes as $troupe) {
            $unite = Unite::getById($troupe->unite_id);
            if($unite) {
                $puissance = $atk ? $unite->atk : $unite->def;

                // Puissance des unités en pleine santé
                $result += $puissance * $troupe->nb;

                // Puissance des unités blessées : 0,5 de la puissance normale
                $result += ($puissance / 2) * $troupe->nb_blessees;
                
                // Puissance des unités fortifiées (en défense seulement) : 1,2 de la puissance normale
                if(!$atk) {
                    $result += ($puissance * 1.2) * $troupe->nb_fortifiees;
                }
            }
        }
        return $result;
    }
}
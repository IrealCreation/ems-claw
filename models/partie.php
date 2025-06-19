<?php 

class Partie extends Db {

    protected static string $db_table = "partie";

    protected static array $ignored_columns = [];

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

}
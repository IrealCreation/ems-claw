<?php 

/**
 * Type d'unité qui peut être recrutées par un clan ou par la vie scolaire
 */
class Unite {

    public static array $liste = [];

    public readonly int $id;

    /**
     * Nom d'affichage l'unité
     *
     * @var string
     */
    public string $nom;

    /**
     * Coût en ressources pour recruter cette unité
     *
     * @var int
     */
    public int $cout = 0;
    
    /**
     * Puissance de l'unité en attaquant une salle
     *
     * @var integer
     */
    public int $atk = 0;
    
    /**
     * Puissance de l'unité en défendant une salle
     *
     * @var integer
     */
    public int $def = 0;

    /**
     * True si l'unité est recrutable par les lycéens, false si recrutable par la vie scolaire
     *
     * @var boolean
     */
    public bool $lyceen;

    public function __construct(string $nom, int $cout, int $atk, int $def, bool $lyceen = true) {
        $this->id = count(self::$liste) + 1; // Génération d'un ID unique basé sur la taille de la liste statique
        $this->nom = $nom;
        $this->cout = $cout;
        $this->atk = $atk;
        $this->def = $def;
        $this->lyceen = $lyceen;

        // Ajout de l'unité à la liste statique
        self::$liste[$this->id] = $this;
    }

    /**
     * Initialisation des unités prédéfinies
     *
     * @return void
     */
    public static function init() {
        // Unités lycéennes
        new Unite("Militant", 10, 10, 10);
        new Unite("Casseur", 20, 25, 15);
        new Unite("Gardien", 20, 15, 25);

        // Unités de la vie scolaire
        new Unite("Surveillant", 15, 15, 15, false);
        new Unite("CRS", 30, 25, 35, false);
        new Unite("Équipe Mobile de Sécurité", 50, 60, 40, false);
    }
}
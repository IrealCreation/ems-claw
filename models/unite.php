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

    public function __construct(string $nom, int $cout, int $atk, int $def) {
        $this->id = count(self::$liste) + 1; // Génération d'un ID unique basé sur la taille de la liste statique
        $this->nom = $nom;
        $this->cout = $cout;
        $this->atk = $atk;
        $this->def = $def;

        // Ajout de l'unité à la liste statique
        self::$liste[$this->id] = $this;
    }
}
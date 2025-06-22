<?php 

/**
 * Idéologies qui peuvent être adoptée par un clan lycéen, chacune conférant des bonus spécifiques
 */
enum Ideologie {
    case Ecolo;
    case Goth;
    case Syndic;
    case Gangsta;
    case Geek;

    /**
     * Renvoie le nom de cette idéologie pour affichage
     *
     * @param boolean $pluriel False si affiché au singulier (par défaut), True si affiché au pluriel
     * @return string
     */
    public function nom($pluriel = false): string {
        $return = match($this) {
            Ideologie::Ecolo => "Écologiste",
            Ideologie::Goth => "Gothique",
            Ideologie::Syndic => "Syndicaliste",
            Ideologie::Gangsta => "Gangsta",
            Ideologie::Geek => "Geek",
        };
        if($pluriel)
            $return .= "s";
        return $return;
    }

    /**
     * Renvoie l'icone de cette idéologie
     *
     * @return string
     */
    public function icone(): string {
        // TODO
        return "";
    }
}
<?php
class PlanningCellule{
    private $data;

    public function __construct($numJour, $heureDebut, $heureFin, $bgColor, $contenu) {
        $this->numjour = $numJour;
        $this->heureDebut = $heureDebut;
        $this->heureFin = $heureFin;
        $this->bgColor = $bgColor;
        $this->contenu = $contenu;
    }

    public function __set($name, $value){
        if ($name == 'heureDebut' || $name == 'heureFin'){
            $tabHeure = explode(':', $value);
            $value = (int)$tabHeure[0];
            if ($tabHeure[1] == 30)
                $value += 0.5;
            $value = $value*60;
        }
        $this->data[$name] = $value;
    }

    public function __get($name){
        if (array_key_exists($name, $this->data)){
            return $this->data[$name];
        }
        $trace = debug_backtrace();
        trigger_error('Propriété non définie via __get():' . $name .
            ' dans ' . $trace[0]['file'] .
            ' à la ligne ' . $trace[0]['line'], E_USER_NOTICE);
        return null;
    }

    public function __toString(){
        $str = 'heure début : '.$this->heureDebut."<br />\n";
        $str .= 'heure fin : '.$this->heureFin."<br />\n";
        $str .= 'couleur : '.$this->bgColor."<br />\n";
        $str .= 'contenu : '.$this->contenu."<br />\n";
        return $str;
    }
}

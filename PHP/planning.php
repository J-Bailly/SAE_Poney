<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <nav class="navigation">
            <a href="index.php">Accueil</a>
            <a href="planning.php">Planning</a>
            <a href="#">Nous Contacter</a>
        </nav>
        <div>
            <a href="Template/signup.php"><button class="sign-up-button">S'inscrire</button></a>
            <a href="Template/login.php"><button class="sign-up-button">Se connecter</button></a>
        </div>
    </header>
<?php
class PlanningCellule {
    public $numJour;
    public $heureDebut;
    public $heureFin;
    public $contenu;
    public $bgColor;

    public function __construct($numJour, $heureDebut, $heureFin, $contenu, $bgColor = "#FFFFFF") {
        $this->numJour = $numJour;
        $this->heureDebut = $heureDebut;
        $this->heureFin = $heureFin;
        $this->contenu = $contenu;
        $this->bgColor = $bgColor;
    }
}

class Planning {
    private $joursFr = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];

    private $jourDebut;
    private $jourFin;
    private $heureDebut;
    private $heureFin;
    private $pas;
    private $minuteKeys;
    private $contenu;
    private $tabSemaine;

    const htmlSpace = "&nbsp;";
    const htmlEmptyCell = "<td>&nbsp;</td>";
    const htmlCellOpen = "<td>";
    const htmlCellClose = "</td>";
    const htmlRowOpen = "<tr>";
    const htmlRowClose = "</tr>";
    const htmlTableOpen = "<table class='tabPlanning'>";
    const htmlTableClose = "</table>";
    const separateurHeure = "h";

    public function __construct($jourDebut = 0, $jourFin = 5, $heureDebut = 480, $heureFin = 1140, $pas = 60, $contenu = Array()) {
        $this->jourDebut = $jourDebut;
        $this->jourFin = $jourFin;
        $this->heureDebut = $heureDebut;
        $this->heureFin = $heureFin;
        $this->pas = $pas;

        // Exemple de contenu en dur (à remplacer par des données de base de données plus tard)
        $this->contenu = [
            new PlanningCellule(0, 540, 600, "Cours d'équitation", "#FFDDC1"),  // Lundi de 9h à 10h
            new PlanningCellule(1, 600, 660, "Balade en forêt", "#C1FFD7"),      // Mardi de 10h à 11h
            new PlanningCellule(2, 660, 720, "Soin des chevaux", "#C1E1FF"),    // Mercredi de 11h à 12h
            new PlanningCellule(3, 780, 840, "Cours avancé", "#FFC1DD"),        // Jeudi de 13h à 14h
            new PlanningCellule(4, 840, 900, "Atelier soin", "#FFE4C1"),        // Vendredi de 14h à 15h
        ];

        $this->initTableauSemaine();
        $this->insererContenus($this->contenu);
    }

    private function genererMinutesKeys() {
        $keys = Array();
        for ($key = $this->heureDebut; $key < $this->heureFin; $key += $this->pas) {
            $keys[] = $key;
        }
        $this->minuteKeys = $keys;
        return $keys;
    }

    private function initTableauJour() {
        if ($this->pas != 0) {
            $numCells = ($this->heureFin - $this->heureDebut) / $this->pas;
        } else {
            echo 'Erreur: pas == 0 !!';
        }
        $keys = $this->genererMinutesKeys();
        $tabJour = array_fill_keys($keys, self::htmlEmptyCell);
        return $tabJour;
    }

    private function initTableauSemaine() {
        $this->tabSemaine = Array();
        $tabJour = $this->initTableauJour();
        for ($i = $this->jourDebut; $i <= $this->jourFin; $i++) {
            $this->tabSemaine[$i] = $tabJour;
        }
    }

    private function getNumeroCellule($minutesDebut, $minutesFin) {
        return ($minutesFin - $minutesDebut) / $this->pas;
    }

    private function insererContenus($contenuPlanning) {
        foreach ($contenuPlanning as $contenuCellule) {
            $this->insererContenu($contenuCellule);
        }
    }

    private function insererContenu($contenuCellule) {
        $duree = $this->getNumeroCellule($contenuCellule->heureDebut, $contenuCellule->heureFin);
        $contenu = $contenuCellule->contenu . '<br />';
        $contenu .= $this->convertirMinutesEnHeuresMinutes($contenuCellule->heureDebut);
        $contenu .= ' - ' . $this->convertirMinutesEnHeuresMinutes($contenuCellule->heureFin);

        $this->tabSemaine[$contenuCellule->numJour][$contenuCellule->heureDebut] = 
            $this->genererCelluleHTML($contenu, $duree, '', $contenuCellule->bgColor);

        $key = $contenuCellule->heureDebut;
        for ($cpt = $duree - 1; $cpt > 0; $cpt--) {
            $key += $this->pas;
            $this->tabSemaine[$contenuCellule->numJour][$key] = '';
        }
    }

    public function genererHtmlTable() {
        $htmlTable = self::htmlTableOpen;
        $htmlTable .= $this->genererBandeauJours();
        $key = $this->heureDebut;
        $keyEnd = $this->heureFin;

        for (; $key < $keyEnd; $key += $this->pas) {
            $htmlTable .= self::htmlRowOpen;
            $htmlTable .= '<td class="cellHour">' . $this->convertirMinutesEnHeuresMinutes($key) . '</td>';
            foreach ($this->tabSemaine as $tabHeures) {
                $htmlTable .= $tabHeures[$key];
            }
            $htmlTable .= self::htmlRowClose;
        }

        $htmlTable .= self::htmlTableClose;
        return $htmlTable;
    }

    public function afficherHtmlTable() {
        $html = $this->genererHtmlTable();
        echo $html; // Affiche le HTML généré sans l'échapper
    }

    private function genererBandeauJours() {
        $daysLine = self::htmlRowOpen;
        $daysLine .= $this->genererCelluleHTML(self::htmlSpace);
        for ($day = $this->jourDebut; $day <= $this->jourFin; $day++) {
            $daysLine .= $this->genererCelluleHTML($this->jourFr($day), '', 'cellDay');
        }
        $daysLine .= self::htmlRowClose;
        return $daysLine;
    }

    private function genererCelluleHTML($contenuCellule, $colspan = '', $class = '', $bgColor = '') {
        $celluleHTML = '<td';
        if (!empty($colspan)) {
            $celluleHTML .= ' rowspan="' . $colspan . '"';
        }
        if (!empty($class)) {
            $celluleHTML .= ' class="' . $class . '"';
        }
        if (!empty($bgColor)) {
            $celluleHTML .= ' bgcolor="' . $bgColor . '"';
        }
        $celluleHTML .= '>';
        $celluleHTML .= $contenuCellule;
        $celluleHTML .= '</td>';
        return $celluleHTML;
    }

    private function jourFr($dayNum) {
        return $this->joursFr[$dayNum];
    }

    private function convertirMinutesEnHeuresMinutes($minutes) {
        $heure = floor($minutes / 60);
        $minutes = $minutes % 60;
        $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);
        return $heure . self::separateurHeure . $minutes;
    }
}
// Créer une instance de la classe Planning
$planning = new Planning();
$planning->afficherHtmlTable();
?>

</body>
</html>
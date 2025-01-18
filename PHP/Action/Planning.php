<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<?php require('../Template/header.php');?>
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
    private $datesSemaine = []; // Tableau pour stocker les dates de la semaine

    const htmlSpace = "&nbsp;";
    const htmlEmptyCell = "<td>&nbsp;</td>";
    const htmlCellOpen = "<td>";
    const htmlCellClose = "</td>";
    const htmlRowOpen = "<tr>";
    const htmlRowClose = "</tr>";
    const htmlTableOpen = "<table class='tabPlanning'>";
    const htmlTableClose = "</table>";
    const separateurHeure = "h";

    public function __construct($jourDebut = 0, $jourFin = 5, $heureDebut = 480, $heureFin = 1140, $pas = 60, $contenu = Array(), $startDate) {
        $this->jourDebut = $jourDebut;
        $this->jourFin = $jourFin;
        $this->heureDebut = $heureDebut;
        $this->heureFin = $heureFin;
        $this->pas = $pas;

        $startDate = $startDate ?? date('Y-m-d'); // Si pas de date donnée, prendre aujourd'hui
        $this->calculerDatesSemaine($startDate);

        // Exemple de contenu en dur (à remplacer par des données de base de données plus tard)
        $this->contenu = [
            new PlanningCellule(0, 540, 600, "Cours d'équitation", "#FFDDC1"),  // Lundi de 9h à 10h
            new PlanningCellule(1, 600, 660, "Balade en forêt", "#C1FFD7"),      // Mardi de 10h à 11h
            new PlanningCellule(2, 660, 720, "Soin des chevaux", "#C1E1FF"),    // Mercredi de 11h à 12h
            new PlanningCellule(3, 780, 840, "Cours avancé", "#FFC1DD"),        // Jeudi de 13h à 14h
            new PlanningCellule(4, 840, 900, "Atelier soin", "#FFE4C1"),         // Vendredi de 14h à 15h
            new PlanningCellule(1, 1050, 1140, 'Dressage', '#008000'),          // Mardi de 17h30 à 19h
            new PlanningCellule(0, 630, 720, 'Obstacle', '#7CCAF4'),            // Lundi de 10h30 à 12h
            new PlanningCellule(2, 750, 840, 'Pony Games', '#7CCAF4'),          // Mercredi de 12h30 à 14h
            new PlanningCellule(3, 870, 960, 'Randonnée', '#7CCAF4'),           // Jeudi de 14h30 à 16h
            new PlanningCellule(5, 660, 750, 'Voltige', '#318CE7'),             // Samedi de 11h à 12h30
            new PlanningCellule(4, 780, 870, 'Dressage', '#318CE7'),            // Vendredi de 13h à 14h30
            new PlanningCellule(3, 900, 990, 'Obstacle', '#008000'),            // Jeudi de 15h à 16h30
            new PlanningCellule(2, 1020, 1110, 'Pony Games', '#008000'),        // Mercredi de 17h à 18h30
            new PlanningCellule(0, 1140, 1230, 'Randonnée', '#008000'),         // Lundi de 19h à 20h30
            new PlanningCellule(4, 1260, 1350, 'Voltige', '#008000'),           // Vendredi de 21h à 22h30
            new PlanningCellule(3, 690, 780, 'Obstacle', '#7CCAF4'),            // Jeudi de 11h30 à 13h
            new PlanningCellule(1, 810, 900, 'Pony Games', '#008000'),          // Mardi de 13h30 à 15h
            new PlanningCellule(5, 930, 1020, 'Dressage', '#318CE7'),           // Samedi de 15h30 à 17h
            new PlanningCellule(4, 1050, 1140, 'Randonnée', '#008000'),         // Vendredi de 17h30 à 19h
            new PlanningCellule(4, 1170, 1260, 'Obstacle', '#318CE7'),          // Vendredi de 19h30 à 21h
            new PlanningCellule(2, 750, 840, 'Voltige', '#318CE7'),             // Mercredi de 12h30 à 14h
            new PlanningCellule(5, 870, 960, 'Obstacle', '#008000')             // Samedi de 14h30 à 16h

           // Dimanche de 17h à 18h30


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
            $dayLabel = $this->jourFr($day) . '<br>' . $this->datesSemaine[$day];
            $daysLine .= $this->genererCelluleHTML($dayLabel, '', 'cellDay');
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

    private function calculerDatesSemaine($startDate) {
    $date = new DateTime($startDate);
    $date->modify('last Monday'); // Aller au lundi de la semaine
    for ($i = 0; $i <= $this->jourFin - $this->jourDebut; $i++) {
        $this->datesSemaine[] = $date->format('d-m-Y'); // Ajouter la date au format YYYY-MM-DD
        $date->modify('+1 day'); // Passer au jour suivant
        }
    }

    private function changerSemaine($startDate) {

    }
}
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
$planning = new Planning(0, 5, 480, 1140, 60, $contenusCellules, $startDate);
$planning->afficherHtmlTable();
?>

</body>
</html>
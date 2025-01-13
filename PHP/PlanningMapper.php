<?php
Class PlanningMapper{
    private static $instance;

    private function __construct() {}

    public static function getInstance(){
        if (!isset(self::$instance)){
            $c = __CLASS__;
            self::$instance = new $c();
        }
        return self::$instance;
    }

    public function genererPlanningCellule($cours){
        $contenuCellule = '<b>'.$cours->getAlphaFormateurs()->getPrenomFormateur().'</b><br />'
        .$cours->getAlphaNiveau()->getLibelleNiveau();
        $planningContent = new PlanningCellule($cours->getJour(),
        $cours->getHeureDebut(), $cours->getHeureFin(),
        $cours->getAlphaNiveau()->getCodeCouleur(),
        $contenuCellule);
        return $planningContent;
    }

    public function __clone() {
        trigger_error('Le clônage n\'est pas autorisé.', E_USER_ERROR);
    }
}
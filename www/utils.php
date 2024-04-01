<?php

class utils
{
    private $certificates;
    private $trainings;
    private $trainingActions;

    public function __construct()
    {
        $this->certificates = array('NB' => 'Non breveté', 'SDI' => 'Scuba Diving Introductory', 'P12E' => 'Basic Diver P12E', 'P20E' => 'Supervised Diver 1* P20E', 'P20A' => 'Diver 1* P20A'
        , 'P30A' => 'Diver 2* P30A', 'P40E' => 'Diver 2* Deep P40E', 'P40A' => 'Diver 3* P40A', 'P60A' => 'Diver 3* Deep P60A', 'DG' => 'Dive Guide 4*'
        , 'AI' => 'Assistant Instructor', 'I1' => 'Instructor N1', 'I2' => 'Instructor N2', 'I3' => 'Instructor N3', 'P12E*' => 'Non Adip P12E (basic)', 'P20E*' => 'Non Adip P20E (1*)', 'P20A*' => 'Non Adip P20A (2*)'
        , 'P30A*' => 'Non Adip P30A (2*Deep)', 'P40A*' => 'Non Adip P40A (3*)', 'DG*' => 'Non Adip Dive guide (4*)'
        , 'AI*' => 'Non Adip Assistant Instructor', 'I1*' => 'Non Adip Instructor N1', 'I2*' => 'Non Adip Instructor N2', 'I3*' => 'Non Adip Instructor N3');

        $this->trainings = array(
            'SDI' => 'Scuba Diving Introductory',
            'P12E' => 'Basic Diver P12E',
            'P20E' => 'Supervised Diver 1* P20E',
            'P20A' => 'Diver 1* P20A',
            'P30A' => 'Diver 2* P30A',
            'P40E' => 'Diver 2* Deep P40E',
            'P40A' => 'Diver 3* P40A',
            'P60A' => 'Diver 3* Deep P60A',
            'DG' => 'Dive Guide 4*',
            'AI' => 'Assistant Instructor',
            'I1' => 'Instructor N1',
            'I2' => 'Instructor N2',
            'I3' => 'Instructor N3',
            'MEDIC' => 'Dive Medic',
            'MEDIC_R' => 'Dive Medic Renewal',
            'OTHER' => 'Autre'
        );

        $this->trainingActions = array(
            'CREATED' => 'Création',
            'GENERATED' => 'Génération du dossier ADIP',
            'SWIM' => 'Démarrage de la formation en piscine',
            'SWIM_OK' => 'Validation en piscine',
            'THEO' => 'Démarrage de la formation théorique',
            'THEO_OK' => 'Validation de la théorie',
            'EXT' => 'Démarrage des exércices en extérieur',
            'EXT_OK' => 'Validation des exercices en extérieur',
            'BAPT' => 'Plongée baptême',
            'END' => 'Fin de la formation',
            'COMMAND' => 'Commande du brevet',
            'RECEPT' => 'Réception du brevet',
            'GIVEN' => 'Délivrance du brevet',
            'OTHER' => 'Autre'
        );
    }

    public function normalize($entry) {
        $entry = preg_replace('/[éèëê]/ui',"e",$entry);
        $entry = preg_replace('/[àâä]/ui',"a",$entry);
        $entry = preg_replace('/[îï]/ui',"i",$entry);
        $entry = preg_replace('/[ôö]/ui',"o",$entry);
        $entry = preg_replace('/[ûü]/ui',"u",$entry);
        $entry = preg_replace('/[ç]/ui',"ç",$entry);
        $entry = preg_replace('/[^A-Za-z0-9\.]/ui',"",$entry);
        return $entry;
    }

    public function getCertificates() {
        return $this->certificates;
    }

    public function getCertificateLabel($certificateCode) {
        $exists = array_key_exists($certificateCode, $this->certificates);
        if ($exists) {
            return $this->certificates[$certificateCode];
        }
        return "Aucun brevet";
    }

    public function getTrainings(): array
    {
        return $this->trainings;
    }

    public function getTrainingLabel($trainingCode) {
        $exists = array_key_exists($trainingCode, $this->trainings);
        if ($exists) {
            return $this->trainings[$trainingCode];
        }
        return "Inconnu";
    }

    public function getTrainingActions(): array
    {
        return $this->trainingActions;
    }

    public function getTrainingActionLabel($trainingActionCode) {
        $exists = array_key_exists($trainingActionCode, $this->trainingActions);
        if ($exists) {
            return $this->trainingActions[$trainingActionCode];
        }
        return "Inconnu";
    }


}

?>
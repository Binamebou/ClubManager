<?php

class utils
{
    private $certificates;

    public function __construct()
    {
        $this->certificates = array('NB' => 'Non breveté', 'SDI' => 'Scuba Diving Introductory', 'P12E' => 'Basic Diver P12E', 'P20E' => 'Supervised Diver 1* P20E', 'P20A' => 'Diver 1* P20A'
        , 'P30A' => 'Diver 2* P30A', 'P40E' => 'Diver 2* Deep P40E', 'P40A' => 'Diver 3* P40A', 'P60A' => 'Diver 3* Deep P60A', 'DG' => 'Dive Guide 4*'
        , 'AI' => 'Assistant Instructor', 'I1' => 'Instructor N1', 'I2' => 'Instructor N2', 'I3' => 'Instructor N3', 'P12E*' => 'Non Adip P12E (basic)', 'P20E*' => 'Non Adip P20E (1*)', 'P20A*' => 'Non Adip P20A (2*)'
        , 'P30A*' => 'Non Adip P30A (2*Deep)', 'P40A*' => 'Non Adip P40A (3*)', 'DG*' => 'Non Adip Dive guide (4*)'
        , 'AI*' => 'Non Adip Assistant Instructor', 'I1*' => 'Non Adip Instructor N1', 'I2*' => 'Non Adip Instructor N2', 'I3*' => 'Non Adip Instructor N3');
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

}

?>
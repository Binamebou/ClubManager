<?php

class utils
{

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

}

?>
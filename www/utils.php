<?php

class utils
{

    public function normalize($entry) {
        $entry = preg_replace('/[éèëê]/u',"e",$entry);
        $entry = preg_replace('/[àâä]/u',"a",$entry);
        $entry = preg_replace('/[îï]/u',"i",$entry);
        $entry = preg_replace('/[ôö]/u',"o",$entry);
        $entry = preg_replace('/[ûü]/u',"u",$entry);
        $entry = preg_replace('/[^A-Za-z0-9\.]/',"",$entry);
        return $entry;
    }

}

?>
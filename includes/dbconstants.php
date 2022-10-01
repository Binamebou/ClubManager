<?php

include_once('dbconnection.php');

if (!$constants) {
    $constants = array();

    $sql = "SELECT * FROM myclub_constants";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        foreach ($results as $row) {
            $constants[$row->constant_name] = $row->constant_value;
        }
    }
}


?>
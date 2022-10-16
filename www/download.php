<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
} else if ($_GET['id']) {

    $id = $_GET['id'];
    $sql = "SELECT * from myclub_documents where ID=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $query->execute();
    $document = $query->fetch(PDO::FETCH_OBJ);

    if ($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER'] || $_SESSION['ROLE_INSTRUCTOR'] || $document->MemberId == $_SESSION['userId']) {
        $filePath = $document->Path;
        $fileName = $document->Type."-".$document->ValidFrom.".".pathinfo($filePath, PATHINFO_EXTENSION);
        if(!empty($fileName) && file_exists($filePath)) {
            // Define headers
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$fileName");
            header("Content-Type: application/octet-stream");
            header("Content-Transfer-Encoding: binary");

            // Read the file
            readfile($filePath);
            exit();
        }
    }
}
?>
<h1>Vous n'avez pas accès à ce document</h1>




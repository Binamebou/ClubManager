<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
include('../includes/dbconstants.php');
require_once('utils.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
} else if (!($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER'] || $_SESSION['ROLE_INSTRUCTOR'])) {
    header('location:dashboard.php');
} else {

    require_once('xls/xlsxwriter.class.php');

    $dataActive = array();
    $i = 0;

    $sql = "SELECT *, IFNULL((select 'ok' from myclub_documents where Type = 'Assurance DAN' and MemberId = myclub_member.ID and ValidFrom < CURDATE() and ValidTo BETWEEN CURDATE() + INTERVAL 1 MONTH AND CURDATE() + INTERVAL 1 YEAR
                                       union
                                       select 'ok-' from myclub_documents where Type = 'Assurance DAN' and MemberId = myclub_member.ID and ValidFrom < CURDATE() and ValidTo > CURDATE() and ValidTo < CURDATE() + INTERVAL 1 MONTH order by 1 limit 1), '-') as DAN,
                                   IFNULL((select 'ok' from myclub_documents where Type = 'Certificat Médical' and MemberId = myclub_member.ID and ValidFrom < CURDATE() and ValidTo BETWEEN CURDATE() + INTERVAL 1 MONTH AND CURDATE() + INTERVAL 1 YEAR
                                           union
                                           select 'ok-' from myclub_documents where Type = 'Certificat Médical' and MemberId = myclub_member.ID and ValidFrom < CURDATE() and ValidTo > CURDATE() and ValidTo < CURDATE() + INTERVAL 1 MONTH order by 1 limit 1), '-') as MED,
                                   IFNULL((select 'ok' from myclub_membership where  MemberId = myclub_member.ID and Year = year(curdate()) order by 1 limit 1), '-') as COT from myclub_member where active = 1 order by LastName, FirstName";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    $utils = new utils();
    if ($query->rowCount() > 0) {
        foreach ($results as $row) {
            $dataActive[$i][0] = date("d/m/Y", strtotime($row->LastUpdate));
            $dataActive[$i][1] = $row->LastName;
            $dataActive[$i][2] = $row->FirstName;
            $dataActive[$i][3] = $utils->getCertificateLabel($row->HighestCertificate);
            $dataActive[$i][4] = date("d/m/Y", strtotime($row->BirthDate));
            $dataActive[$i][5] = $row->MobileNumber;
            $dataActive[$i][6] = $row->Email;
            $dataActive[$i][7] = $row->Address;
            $dataActive[$i][8] = $row->PostalCode;
            $dataActive[$i][9] = $row->City;
            $dataActive[$i][10] = $row->Country;
            $dataActive[$i][11] = $row->COT;
            $dataActive[$i][12] = $row->DAN;
            $dataActive[$i][13] = $row->MED;
            $dataActive[$i][14] = $row->MemberType;
            $dataActive[$i][15] = date("d/m/Y", strtotime($row->ArrivalDate));
            $i++;
        }
    }

    $dataInactive = array();
    $i = 0;

    $sql = "SELECT * from myclub_member where active = 0 order by LastName, FirstName";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $row) {
            $dataInactive[$i][0] = date("d/m/Y", strtotime($row->LastUpdate));
            $dataInactive[$i][1] = $row->LastName;
            $dataInactive[$i][2] = $row->FirstName;
            $dataInactive[$i][3] = date("d/m/Y", strtotime($row->BirthDate));
            $dataInactive[$i][4] = $row->MobileNumber;
            $dataInactive[$i][5] = $row->Email;
            $dataInactive[$i][6] = $row->Address;
            $dataInactive[$i][7] = $row->PostalCode;
            $dataInactive[$i][8] = $row->City;
            $dataInactive[$i][9] = $row->Country;
            $i++;
        }
    }

    $header = array(
        'Modifié le'=>'string',
        'Nom'=>'string',
        'Prénom'=>'string',
        'Brevet'=>'string',
        'Date de naissance'=>'string',
        'Téléphone'=>'string',
        'Email'=>'string',
        'Adresse'=>'string',
        'Code postal'=>'string',
        'Ville'=>'string',
        'Pays'=>'string',
        'Cotisation'=>'string',
        'Assurance'=>'string',
        'Certificat médical'=>'string',
        'Type de membre'=>'string',
        'Arrivée au club'=>'string',
    );

    $activeMembersTitle = 'Membres actifs au '. date("d/m/Y");
    $writer = new XLSXWriter();
    $writer->writeSheetHeader($activeMembersTitle, $header);
    foreach($dataActive as $row)
        $writer->writeSheetRow($activeMembersTitle, $row);

    $header = array(
        'Modifié le'=>'string',
        'Nom'=>'string',
        'Prénom'=>'string',
        'Date de naissance'=>'string',
        'Téléphone'=>'string',
        'Email'=>'string',
        'Adresse'=>'string',
        'Code postal'=>'string',
        'Ville'=>'string',
        'Pays'=>'string'
    );

    $inactiveMembersTitle = 'Membres archivés';
    $writer->writeSheetHeader($inactiveMembersTitle, $header);
    foreach($dataInactive as $row)
        $writer->writeSheetRow($inactiveMembersTitle, $row);

    $file = 'Amphiprion-membres-'. date("Y-m-d").'.xlsx';
    $writer->writeToFile($file);

    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        unlink($file);
        exit;
    }

    exit(0);
}


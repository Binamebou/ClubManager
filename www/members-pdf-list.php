<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
include('../includes/dbconstants.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
} else if (!($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER'])) {
    header('location:dashboard.php');
} else {

    require_once('pdf/tcpdf_include.php');
// create new PDF document
    $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor($constants['SITE_NAME']);
    $pdf->SetTitle('Liste des membres');
    $pdf->SetSubject('Membres au '.date("d/m/Y"));

// set default header data
    $pdf->SetHeaderData("", 0, "Liste des membres au  ".date("d/m/Y"), "", array(0, 64, 255), array(0, 64, 128));
    $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// set header and footer fonts
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// ---------------------------------------------------------

// set default font subsetting mode
    $pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
    $pdf->SetFont('dejavusans', '', 8, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
    $pdf->AddPage();

// set text shadow effect
    $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));

// Set some content to print
    $html = "<table><thead><tr><td>Nom</td><td>Prénom</td><td>Naissance</td><td>Téléphone</td><td>Email</td><td>Adresse</td></tr></thead>";

    $sql = "SELECT * from myclub_member order by LastName, FirstName";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        foreach ($results as $row) {
            $html .= "<tr><td>$row->LastName</td><td>$row->FirstName</td><td>".date("d/m/Y", strtotime($row->BirthDate))."</td><td>$row->MobileNumber</td><td>$row->Email</td><td>$row->Address , $row->PostalCode $row->City ($row->Country)</td></tr>";
        }
    }

    $html .= "</table>";

// Print text using writeHTMLCell()
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
    $pdf->Output('members.pdf', 'I');
}


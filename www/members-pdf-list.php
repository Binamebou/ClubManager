<?php
session_start();
error_reporting(0);
include('../includes/dbconnection.php');
include('../includes/dbconstants.php');
if (!$_SESSION['userId']) {
    header('location:logout.php');
} else if (!($_SESSION['ROLE_ADMIN'] || $_SESSION['ROLE_MANAGER'] || $_SESSION['ROLE_INSTRUCTOR'])) {
    header('location:dashboard.php');
} else {

    require_once('pdf/tcpdf_include.php');

// extend TCPF with custom functions
    class MYPDF extends TCPDF
    {

        // Load table data from file
        public function LoadData($dbh)
        {
            $data = array();
            $i = 0;

            $sql = "SELECT * from myclub_member order by LastName, FirstName";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);

            if ($query->rowCount() > 0) {
                foreach ($results as $row) {
                    $date = new DateTime($row->LastUpdate);
                    $now = new DateTime();
                    if (date_add($date,date_interval_create_from_date_string("30 days")) > $now) {
                        $data[$i][0] = "(*)".$row->LastName;
                    } else {
                        $data[$i][0] = $row->LastName;
                    }
                    $data[$i][1] = $row->FirstName;
                    $data[$i][2] = date("d/m/Y", strtotime($row->BirthDate));
                    $data[$i][3] = $row->MobileNumber;
                    $data[$i][4] = $row->Email;
                    $data[$i][5] = $row->Address . ", " . $row->PostalCode . " " . $row->City . " (" . $row->Country . ")";
                    $i++;
                }
            }
            return $data;
        }

        // Colored table
        public function MembersTable($header, $data)
        {
            // Colors, line width and bold font
            $this->SetFillColor(0, 37, 97);
            $this->SetTextColor(255);
            $this->SetDrawColor(0, 37, 97);
            $this->SetLineWidth(0.3);
            $this->SetFont('', 'B');
            // Header
            $w = array(40, 35, 20, 30, 52, 90);
            $num_headers = count($header);
            for ($i = 0; $i < $num_headers; ++$i) {
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
            }
            $this->Ln();
            // Color and font restoration
            $this->SetFillColor(224, 235, 255);
            $this->SetTextColor(0);
            $this->SetFont('');
            // Data
            $fill = 0;
            foreach ($data as $row) {
                $this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
                $this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
                $this->Cell($w[2], 6, $row[2], 'LR', 0, 'L', $fill);
                $this->Cell($w[3], 6, $row[3], 'LR', 0, 'L', $fill);
                $this->Cell($w[4], 6, $row[4], 'LR', 0, 'L', $fill);
                $this->Cell($w[5], 6, $row[5], 'LR', 0, 'L', $fill);
                $this->Ln();
                $fill = !$fill;
            }
            $this->Cell(array_sum($w), 0, '', 'T');
        }
    }

// create new PDF document
    $pdf = new MYPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor($constants['SITE_NAME']);
    $pdf->SetTitle('Liste des membres');
    $pdf->SetSubject('Membres au ' . date("d/m/Y"));

// set default header data
    $pdf->SetHeaderData("", 0, "Liste des membres au  " . date("d/m/Y"), "", array(0, 64, 255), array(0, 64, 128));
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

    $pdf->Text(15,18,"(*) Ajouté ou modifié dans les 30 derniers jours");
    $pdf->ln();

    // column titles
    $header = array('Nom', 'Prénom', 'Naissance', 'Téléphone', 'email', 'Adresse');
    // data loading
    $data = $pdf->LoadData($dbh);
    // print colored table
    $pdf->MembersTable($header, $data);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
    $pdf->Output('members-'.date("Y-m-d").'.pdf', 'I');
}


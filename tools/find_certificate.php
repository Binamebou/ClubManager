<!DOCTYPE HTML>
<html lang="fr">
<body>

<?php

for ($index = 26000; $index <= 26999; $index++) {
//  echo '<a href = "https://adip.be/apercu_fiche_eleve.php?id='.base64_encode($index).'&filetype=ZnVsbGNhcmQ=&lang=ZnI=" target="_blank">'.$index."</a><br />";

    $page = "https://www.adip-international.org/d.php?id=".base64_encode($index);

    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTMLFile($page);

    $certificate = $doc->getElementsByTagName("table")[0]
        ->getElementsByTagName("tr")[0]
        ->getElementsByTagName("td")[1]
        ->getElementsByTagName("div")[0]
        ->getElementsByTagName("p")[0]->textContent;

    if (str_contains($certificate,"Belgium")) {
        $certificate_type = $doc->getElementsByTagName("table")[0]
            ->getElementsByTagName("tr")[0]
            ->getElementsByTagName("td")[1]
            ->getElementsByTagName("div")[0]
            ->getElementsByTagName("h3")[0]
            ->getElementsByTagName("b")[0]->nodeValue;

        echo $certificate . " - " .$certificate_type. " : " . '<a href = "' . $page . '" target="_blank">' . $index . "</a><br />";
    }
}

?>

</body>

<?php
session_start();
error_reporting(0);

if (!$_SESSION['userId']) {
    header('location:logout.php');
} else {
    $image = $_POST['image'];

    list($type, $image) = explode(';', $image);
    list(, $image) = explode(',', $image);

    $image = base64_decode($image);
    $image_path = "../documents/" . $_SESSION['lastName'] . " " . $_SESSION['firstName'] . "/photo.png";
    file_put_contents($image_path, $image);
    echo 'Image sauvegardée';
}
?>
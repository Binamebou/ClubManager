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
    $target_dir = "../documents/" . $_SESSION['lastName'] . " " . $_SESSION['firstName'] . "/";
    mkdir($target_dir, 0755, true);
    $image_path = $target_dir . "/photo.png";
    file_put_contents($image_path, $image);
    echo 'Image sauvegardée';
}
?>
<?php
require 'php/sessionManager.php';
require 'models/users.php';
require 'models/photos.php';

adminAccess();
session_start();

if (!isset($_GET["Id"])){


    $_SESSION["Error"] = "ID non trouver/valide pour delete un profil";
    redirect("errorPage.php");
}
    

$currentUserId = (int) $_GET["Id"];

$photosFile = PhotosFile();
$photos = $photosFile->toArray();

for ($i = count($photos) - 1; $i >= 0; $i--) {
    $photo = $photos[$i];

    if ($photo->OwnerId() != $currentUserId)
        continue;

    $photosFile->remove($photo->Id());
}

UsersFile()->remove($currentUserId);
redirect('usersList.php');
<?php
require 'php/sessionManager.php';
require 'models/users.php';
require 'models/photos.php';

adminAccess();
//session_start();

if (!isset($_POST["Id"])){
    onError("ID non trouver/valide pour delete un profil");
}
    

$currentUserId = (int) $_POST["Id"];

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
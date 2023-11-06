<?php
include 'php/sessionManager.php';
include 'models/users.php';
include 'models/photos.php';

adminAccess();

if (!isset($_GET["Id"]))
    redirect("errorPage.php");

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
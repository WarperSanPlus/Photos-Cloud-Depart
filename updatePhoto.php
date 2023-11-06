<?php
require 'php/sessionManager.php';
require 'models/photos.php';
userAccess();
$photo = new Photo($_POST);
PhotosFile()->update($photo);
redirect('photosList.php');

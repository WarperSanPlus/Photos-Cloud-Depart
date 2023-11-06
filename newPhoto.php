<?php
require 'php/sessionManager.php';
require_once 'models/photos.php';

anonymousAccess();
PhotosFile()->add(new Photo($_POST));
redirect('photosList.php'); 
<?php
include 'php/sessionManager.php';
include "models/photos.php";
include "models/users.php";

function createIndicator($src, $title)
{
    return <<<HTML
    <div class="UserAvatarSmall transparentBackground" style="background-image:url('$src')" title="$title"></div>
    HTML;
}

$viewName = "photoList";

userAccess();

$viewTitle = "Photos";

$list = PhotosFile()->toArray();

$viewContent = "<div class='photosLayout'>";

// Charge et affiche les photos
// TODO : photo.render() ?
$isAdmin = isset($_SESSION["isAdmin"]) ? $_SESSION["isAdmin"] : false;

foreach ($list as $photo) {
    $viewContent .= $photo->render($isAdmin);
}

$viewContent = $viewContent . "</div>";

$viewScript = <<<HTML
    <script src='js/session.js'></script>
    <script defer>
        $("#addphotoCmd").hide();
    </script>
HTML;

include "views/master.php";

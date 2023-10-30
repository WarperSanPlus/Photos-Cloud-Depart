<?php
include 'php/sessionManager.php';
include "models/photos.php";
include "models/users.php";

function createIndicator($src, $title) {
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
    $id = strval($photo->id());
    $title = $photo->Title();
    $description = $photo->Description();
    $image = $photo->Image();
    $owner = UsersFile()->Get($photo->OwnerId());
    $ownerName = $owner->Name();
    $ownerAvatar = $owner->Avatar();
    $shared = $photo->Shared() == "true";
    
    $sharedIndicator = "";
    $privateIndicator = "";
    
    $editCmd = "";
    $visible = $shared;

    if (($photo->OwnerId() == (int) $_SESSION["currentUserId"])) {
        $visible = true;
        $editCmd = <<<HTML
            <a href="editPhotoForm.php?id=$id" class="cmdIconSmall fa fa-pencil" title="Editer $title"> </a>
            <a href="confirmDeletePhoto.php?id=$id"class="cmdIconSmall fa fa-trash" title="Effacer $title"> </a>
        HTML;
        
        if ($shared) {
            $sharedIndicator = createIndicator('images/shared.png', 'partagée');
        }
    }

    if ($isAdmin || $visible) {
        if (!$shared && !$visible) { // Disable private indicator if own image is private
        //if (!$shared) { // Show if own image is private 
            $privateIndicator = createIndicator('images/private.png', 'privée');
        }

        $photoHTML = <<<HTML
        <div class="photoLayout" photo_id="$id">
            <div class="photoTitleContainer" title="$description">
                <div class="photoTitle ellipsis">$title</div> $editCmd</div>
            <a href="$image" target="_blank">
                <div class="photoImage" style="background-image:url('$image')">
                    <div class="UserAvatarSmall transparentBackground" style="background-image:url('$ownerAvatar')" title="$ownerName"></div>
                    $sharedIndicator
                    $privateIndicator
                </div>
            </a>
        </div>           
        HTML;
        $viewContent = $viewContent . $photoHTML;
    }
}

$viewContent = $viewContent . "</div>";

$viewScript = <<<HTML
    <script src='js/session.js'></script>
    <script defer>
        $("#addphotoCmd").hide();
    </script>
HTML;

include "views/master.php";

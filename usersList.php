<?php
include 'php/sessionManager.php';
include_once "models/Users.php";

function createGestionIcon($id, $image, $extraValueName = null, $extraValue = null, $url = null) {
    if (!isset($url)) {
        $url = "";
    }
    
    $html = <<< HTML
    <form method='post' action='$url'>
        <input type="hidden" name="targetId" value="$id"> 
    HTML;

    if (isset($extraValueName) && isset($extraValue)) {
        $html.= "<input type='hidden' name='$extraValueName' value='$extraValue'>";
    }

    return $html."<input type='image' name='submit' src='$image' class='GestionIcon'></form>";
}

adminAccess();

$viewTitle = "Liste des usagers";
$list = UsersFile()->toArray();
$viewContent = "";

$currentUserId = isset($_SESSION["currentUserId"]) ? $_SESSION["currentUserId"] : null;

foreach ($list as $User) {
    $id = strval($User->id());
    $email = $User->Email();

    if ($id == $currentUserId || $email == "admin@clg.qc.ca") { // Always hide Super Admin
        continue;
    }

    $name = $User->name();
    $avatar = $User->Avatar();
    $isBlocked = $User->isBlocked();
    $isAdmin = $User->isAdmin();

    // Gestion Icons
    $blockImage = $isBlocked ? "images/blocked.png" : "images/unblocked.png";
    $blocked = createGestionIcon($id, $blockImage, "nextBlock", !$isBlocked, "setBlockUser.php");

    $delete = createGestionIcon($id, 'images/delete.png', '');

    $adminImage = $isAdmin ? "images/admin.png" : "images/user.png";
    $admin = createGestionIcon($id, $adminImage, 'nextAdmin', !$isAdmin,'setAdminPermission.php');

    $UserHTML = <<<HTML
    <div class="UserRow" User_id="$id">
        <div class="UserContainer noselect">
            <div class="UserLayout">
                <div class="UserAvatar" style="background-image:url('$avatar')"></div>
                <div class="UserInfo">
                    <span class="UserName">$name</span>
                    <a href="mailto:$email" class="UserEmail" target="_blank" >$email</a>
                </div>
                <div class="GestionGrid">
                    $blocked
                    $delete
                    $admin
                </div>
            </div>
        </div>
    </div>           
    HTML;
    $viewContent .= $UserHTML;
}

$viewScript = <<<HTML
    <script src='js/session.js'></script>
    <script defer>
        $("#addPhotoCmd").hide();
    </script>
HTML;

include "views/master.php";

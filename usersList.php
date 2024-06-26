<?php
require 'php/sessionManager.php';
require_once "models/Users.php";

/**
 * @param mixed $id ID de l'utilisateur concerné
 * @param mixed $image Lien pour l'image à afficher
 * @param mixed $title Message à afficher lorsque l'utilisateur garde le curseur sur l'image
 * @param mixed $extraValueName Nom du paramètre à envoyer en supplément
 * @param mixed $extraValue Valeur supplémentaire à envoyer
 * @param mixed $url Page à appeler lorsque l'image est cliquée
 */
function createGestionIcon($id, $image, $title, $extraValueName = null, $extraValue = null, $url = null)
{
    if (!isset($url)) {
        $url = "";
    }

    $html = <<<HTML
    <form method='post' action='$url'>
        <input type="hidden" name="targetId" value="$id"> 
    HTML;

    if (isset($extraValueName) && isset($extraValue)) {
        $html .= "<input type='hidden' name='$extraValueName' value='$extraValue'>";
    }

    return $html . "<input type='image' name='submit' src='$image' class='GestionIcon' title='$title'></form>";
}

adminAccess();

$viewTitle = "Liste des usagers";
$list = UsersFile()->toArray();
$viewContent = "";

$currentUserId = isset($_SESSION["currentUserId"]) ? $_SESSION["currentUserId"] : null;

foreach ($list as $User) {
    $id = strval($User->id());
    $email = $User->Email();
    $ids = (int) ($User->id());
    
    // || $email == "admin@clg.qc.ca"
    if ($id == $currentUserId) { // Always hide Super Admin
        continue;
    }

    $name = $User->name();
    $avatar = $User->Avatar();
    $isBlocked = $User->isBlocked();
    $isAdmin = $User->isAdmin();

    // Gestion Icons
    // --- User block ---
    $blockImage = $isBlocked ? "images/blocked.png" : "images/unblocked.png";
    $blocked = createGestionIcon(
        $id,
        $blockImage,
        $isBlocked ? "Débloquer $name" : "Bloquer $name",
        // Title
        "nextBlock",
        !$isBlocked,
        "setBlockUser.php"
    );
    // ---

    // -- Delete user
    $delete = createGestionIcon(
        $id,
        'images/delete.png',
        "Supprimer le compte de $name",
        // Title
        'Id',
        $id,
        'confirmDeleteUser.php'
    );
    // ---

    // --- Admin promote ---
    $adminImage = $isAdmin ? "images/admin.png" : "images/user.png";
    $admin = createGestionIcon(
        $id,
        $adminImage,
        $isAdmin ? "Dégrader $name" : "Promouvoir $name",
        // Title
        'nextAdmin',
        !$isAdmin,
        'setAdminPermission.php'
    );
    // ---

    $viewContent .= <<<HTML
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
                    $admin
                    $delete
                </div>
            </div>
        </div>
    </div>           
    HTML;
}

$viewScript = <<<HTML
    <script src='js/session.js'></script>
    <script defer>
        $("#addPhotoCmd").hide();
    </script>
HTML;

require "views/master.php";

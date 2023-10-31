<?php
function generateLoggedUserMenu($isAdmin)
{
    $loggedUserMenu = "";

    $options = [
        "Déconnexion" => ["logout.php", "fa-sign-out"],
        "Modifier votre profil" => ["editProfilForm.php", "fa-user"],
        "Liste des usagers" => ["usersList.php", "fa-users"],
        "Liste des photos" => ["photosList.php", "fa-image"],
    ];

    // TODO : Make this a model (like users)
    foreach ($options as $key => $value) {

        if ($key == "Liste des usagers" && !$isAdmin) {
            continue;
        }

        $action = isset($value[0]) ? $value[0] : "";
        $extraClass = isset($value[1]) ? $value[1] : "";

        $loggedUserMenu .= <<<HTML
            <a href="$action" class="dropdown-item">
                <i class="menuIcon fa $extraClass mx-2"></i> $key
            </a>
        HTML;
    }
    return $loggedUserMenu;
}

$pageTitle = "Photos Cloud";

if (!isset($viewTitle))
    $viewTitle = "";

if (!isset($viewHeadCustom))
    $viewHeadCustom = "";

if (!isset($viewName))
    $viewName = "";

$loggedUserMenu = <<<HTML
<a href="loginForm.php" class="dropdown-item">
    <i class="menuIcon fa fa-sign-in mx-2"></i> Connexion
</a> 
HTML;

$connectedUserAvatar = <<<HTML
    <div>&nbsp;</div>
HTML;

if (isset($_SESSION["validUser"]) || isset($_SESSION["validAdmin"])) {
    $avatar = $_SESSION["avatar"];
    $userName = $_SESSION["userName"];

    $loggedUserMenu = generateLoggedUserMenu(isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == "true");

    $connectedUserAvatar = <<<HTML
            <div class="UserAvatarSmall" style="background-image:url('$avatar')" title=""$userName></div>
        HTML;
}

$viewMenu = "";

// TODO : Make this a model
if (strcmp($viewName, "photoList") == 0) {
    // toto add more items in popupmenu
    $viewMenu = <<<HTML
         <div class="dropdown-divider"></div>
         <a href="photosList.php?sort=date" class="dropdown-item" id="photosListCmd">
                <i class="menuIcon fa fa-calendar mx-2"></i>Photos par date de création
         </a>
         <a href="photosList.php?sort=owners" class="dropdown-item" id="photosListCmd">
                <i class="menuIcon fa fa-users mx-2"></i>Photos par créateur
         </a>
         <a href="photosList.php?sort=mine" class="dropdown-item" id="photosListCmd">
                <i class="menuIcon fa fa-user mx-2"></i>Voir mes photos
         </a>
        HTML;
}

// --- Header Plus (+) ---
if (isset($headerPlusAction)) {
    if (!isset($headerPlusSubtitle)) {
        $headerPlusSubtitle = "Action";
    }

    $headerPlus = <<<HTML
        <a href="$headerPlusAction" class="cmdIcon fa fa-plus" title="$headerPlusSubtitle"></a>
    HTML;
} else
    $headerPlus = "";
// ---


$viewHead = <<<HTML
    <a href="photosList.php" title="Liste des photos"><img src="images/PhotoCloudLogo.png" class="appLogo"></a>
    <span class="viewTitle">
        $viewTitle 
        $headerPlus
    </span>
    <div class="headerMenusContainer">
        <span>&nbsp</span>
        <!--filler-->
        <a href="editProfilForm.php" title="Modifier votre profil"> $connectedUserAvatar </a>  
        <!-- popup menu -->       
        <div class="dropdown ms-auto">
            <div data-bs-toggle="dropdown" aria-expanded="false">
                <i class="cmdIcon fa fa-ellipsis-vertical"></i>
            </div>
            <div class="dropdown-menu noselect">
                $loggedUserMenu
                $viewMenu
                <div class="dropdown-divider"></div>
                <a href="about.php" class="dropdown-item">
                    <i class="menuIcon fa fa-info-circle mx-2"></i> À propos ...
                </a>
            </div>
        </div>
        <!----------->
    </div>
HTML;
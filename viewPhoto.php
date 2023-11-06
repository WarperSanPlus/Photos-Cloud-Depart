<?php
include 'php/sessionManager.php';

$errorPage = "errorPage.php";

// --- Get Photo ID ---
if (!isset($_GET["Id"])) {
    redirect($errorPage);
}

$id = $_GET["Id"];
// ---

include "models/photos.php";
include "models/users.php";

// --- Get Photo ---
$photo = PhotosFile()->get((int) $id);
if ($photo == null) {
    redirect($errorPage);
}
// ---

// --- Get User ---
$user = UsersFile()->get($photo->OwnerId());

if ($user == null) {
    redirect($errorPage);
}
// ---

$viewContent = <<<HTML
<div style="margin-top:1em;">
HTML;

// --- Username ---
$username = $user->name();

// $viewContent .= $username;
// ---

// --- Avatar ---
$avatar = $user->avatar();

// $viewContent .= <<<HTML
// <div><img src="$avatar"/></div>
// HTML;
// ---

// --- Titre ---
$titre = $photo->Title();

// $viewContent .= $titre;
// ---

// --- Image ---
$image = $photo->Image();

$viewContent .= <<<HTML
<div style="width: fit-content;margin: auto;"><img src="$image" class="photoViewContentBorder" style="max-width:100%;max-height:60vh;"/></div>
HTML;
// ---

// --- Description ---
$description = $photo->Description();

$viewContent .= <<<HTML
<div class="photoViewDescription photoViewContentBorder">$description</div>
HTML;
// ---

// --- Date ---
$date = date("Y-m-d H:i:s", $photo->creationDate());

// $viewContent .= <<<HTML
// <div> <p> cette photo a été posté le " $date "<p><div>
// HTML;
// ---

$userAvatarIndicator = Photo::createIndicator($avatar, $username);

$viewContent = <<<HTML
<div class="photoViewTitle">
    <div style="display: grid;grid-template-columns: auto auto;">
        $userAvatarIndicator
        <div><p class="viewTitle">$username</p></div>
    </div>
    <div style="text-align:center;font-size:2em;text-decoration:underline;font-weight:bold;"><p>$titre</p></div>
    <div><p>$date</p></div>
</div>
$viewContent
HTML;

// --- Titre de la page ---
// $viewTitle = "'$titre' par $username";
$viewTitle = "Photo";
// ---

include "views/master.php";
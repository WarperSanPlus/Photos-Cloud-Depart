<?php
require 'php/sessionManager.php';
userAccess();
$errorPage = "errorPage.php";

// --- Get Photo ID ---
if (!isset($_GET["Id"])) {
    redirect($errorPage);
}

$id = $_GET["Id"];
// ---

require "models/photos.php";
require "models/users.php";

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
$viewContent .= <<<HTML
<div style="text-align:center;font-size:2em;text-decoration:underline;font-weight:bold;"><p>$titre</p></div>
HTML;
// $viewContent .= $titre;


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

//$date = date("l-F-Y H:i:s", $photo->creationDate());

$fmt = datefmt_create(
    'fr-FR',
    IntlDateFormatter::FULL,
    IntlDateFormatter::FULL,
    'America/Los_Angeles',
    IntlDateFormatter::GREGORIAN,
    'cccc d LLLL y HH:mm:ss'
);
$date =datefmt_format($fmt, $photo->creationDate());



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
    <div><p>$date</p></div>
</div>
$viewContent
HTML;

// --- Titre de la page ---
// $viewTitle = "'$titre' par $username";
$viewTitle = "Photo";
// ---

require "views/master.php";
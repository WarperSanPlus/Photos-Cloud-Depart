<?php
require 'php/sessionManager.php';
userAccess();

// --- Get Photo ID ---
if (!isset($_GET["Id"])) {
    onError("Image ID was not found.");
}

$id = (int) $_GET["Id"];
// ---

require "models/photos.php";
require "models/users.php";

// --- Get Photo ---
$photo = PhotosFile()->get($id);
if ($photo == null) {
    onError("No image has the id '$id'.");
}
// ---

// --- Get User ---
$userId = $photo->OwnerId();
$user = UsersFile()->get($userId);

if ($user == null) {
    onError("No user has the id '$userId'.");
}
// ---

// --- Username ---
$username = $user->name();

// --- Avatar ---
$userAvatarIndicator = Photo::createIndicator($user->avatar(), $username);
// ---

$username = <<< HTML
<div><p class="viewTitle">$username</p></div>
HTML;
// ---

// --- Titre ---
$titre = $photo->Title();
$titre = <<<HTML
<div style="text-align:center;font-size:2em;text-decoration:underline;font-weight:bold;"><p>$titre</p></div>
HTML;
// ---

// --- Image ---
$image = $photo->Image();
$image = <<<HTML
<div style="width: 80%;margin: auto;"><img src="$image" class="photoViewContentBorder" style="width:100%;height:100%;"/></div>
HTML;
// ---

// --- Description ---
$description = $photo->Description();

$description = <<<HTML
<div class="photoViewDescription photoViewContentBorder">$description</div>
HTML;
// ---

// --- Date ---
$fmt = datefmt_create(
    'fr-FR',
    IntlDateFormatter::FULL,
    IntlDateFormatter::FULL,
    'America/New_York',
    IntlDateFormatter::GREGORIAN,
    'cccc d LLLL y HH:mm:ss' // lundi 6 novembre 2023 23:23:23
);
$date = datefmt_format($fmt, $photo->creationDate());

$date = <<< HTML
<div><p>$date</p></div>
HTML;
// ---

$viewContent = <<<HTML
<div class="photoViewTitle">
    <div style="display: grid;grid-template-columns: auto auto;">
        $userAvatarIndicator
        $username
    </div>
    $date
</div>
<div style="margin-top:1em;">
    $titre
    $image
    $description
</div>
HTML;

// --- Titre de la page ---
//$viewTitle = "'$titre' par $username";
$viewTitle = "Photo";
// ---

require "views/master.php";
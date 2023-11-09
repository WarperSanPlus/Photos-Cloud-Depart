<?php
require "php/sessionManager.php";

userAccess();

$viewTitle = "Erreur 404!";
$sourceErreur = "Une erreur est survenue.";

if (isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] && isset($_SESSION["Error"])){
    $erreur = $_SESSION["Error"];
    $sourceErreur .= <<< HTML
        <br><br>
        <p style='color:#FF0000'>$erreur</p>
    HTML;
}
    
$viewContent = <<<HTML
    <br>
    <div class="loginForm">
        <h4>$sourceErreur</h4><br><br>
        <h2><a href='photosList.php'>Retourner à la page Photos</a></h2>
    </div>
HTML;

require "views/master.php";
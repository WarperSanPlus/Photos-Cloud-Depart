<?php
$viewTitle = "Erreur 404!";
session_start();
$sourceErreur = "une erreur est sruvenue. Resource introuvable";
if (isset($_SESSION["Error"])){
    $sourceErreur = $_SESSION["Error"] ;
    
}
    
$viewContent = <<<HTML


    <br>
    <div class="loginForm">
    <h4>$sourceErreur</h4><br><br>
    <h2><a href='photosList.php'>retour au photo</a></h2>
    </div>
HTML;

require "views/master.php";
<?php
$viewTitle = "Erreur 404!";
$viewContent = <<<HTML
    <br>
    <div class="loginForm">
    <h4>Ressource introuvable</h4><br><br>
    <h2><a href='photosList.php'>retour au photo</a></h2>
    </div>
HTML;

require "views/master.php";
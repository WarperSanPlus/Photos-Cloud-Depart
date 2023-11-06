<?php
require 'php/sessionManager.php';

anonymousAccess(200);

$viewTitle = "À propos ...";
$viewContent = <<<HTML
     <div class="aboutContainer">
        <h2>Photos Cloud</h2>
        <hr>
        <p>
            Gestionnaire de photos multi-usagers
        </p>
        <p>
            Auteur: Nicolas Chourot
        </p>
        <p>
            Collège Lionel-Groulx, automne 2023
        </p>
    </div>
HTML;
$viewScript = <<<HTML
   <script defer>
        $("#addPhotoCmd").hide();
    </script>
HTML;
require "views/master.php";

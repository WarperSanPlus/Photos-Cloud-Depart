<?php
include 'php/sessionManager.php';
include 'models/users.php';

$errorPage = "errorPage.php";

adminAccess(200);

if (!isset($_POST["Id"]))
    redirect($errorPage);

$user = UsersFile()->get((int) $_POST["Id"]);

if ($user == null)
   redirect($errorPage);

$username = $user->name();
$avatar = $user->avatar();
$viewTitle = "Retrait d'un compte";

$viewContent = <<<HTML
<div class="content loginForm">
    <br>
    <h3> Voulez-vous vraiment effacer le compte suivant? </h3>
    <div class="form">
        <div>
    <img src="$avatar" alt="Photo de l'utilisateur"> 
    <p>$username</p>   
    </div>
        <a href="deleteProfil.php"><button class="form-control btn-danger">Effacer le compte</button>
        <br>
        <a href="usersList.php" class="form-control btn-secondary">Annuler</a>
    </div>
</div>
HTML;

$viewScript = <<<HTML
    <script defer>
        $("#addPhotoCmd").hide();
    </script>
HTML;
include "views/master.php";
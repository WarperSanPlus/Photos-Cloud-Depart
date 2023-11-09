<?php
require 'php/sessionManager.php';
require 'models/users.php';

$errorPage = "errorPage.php";

adminAccess(200);

if (!isset($_POST["Id"]))
    onError("No ID was defined.");

$id = (int) $_POST["Id"];
$user = UsersFile()->get($id);

if ($user == null)
    onError("No user has the ID '$id'.");

$username = $user->name();
$avatar = $user->avatar();
$viewTitle = "Retrait d'un compte";
$url = "deleteProfil.php";
//$url .= "?Id=".$_POST["Id"];

$viewContent = <<<HTML
<div class="content loginForm">
    <br>
    <h3> Voulez-vous vraiment effacer le compte suivant? </h3>
    <div class="form">
        <div>
            <div style="width: fit-content;margin: auto;">
                <img src="$avatar" class="" style="max-width:100%;max-height:60vh;"/>
            </div>
        
        <p>$username</p>   
        </div>
        <a href=$url><button class="form-control btn-danger">Effacer le compte</button>
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
require "views/master.php";
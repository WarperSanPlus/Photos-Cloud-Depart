<?php
    $errorPage = "errorPage.php";
    include 'php/sessionManager.php';
    include 'models/users.php';
    $user = UsersFile()->get( $_SESSION["Ids"]);
            if ($user == null) {
              //  redirect($errorPage);
            }
    $username = $user->name();
    $avatar = $user->avatar();  
    $viewTitle = "Retrait d'un compte";
    
    userAccess(200);
    
    $viewContent = <<<HTML
    <div class="content loginForm">
        <br>
       <h3> Voulez-vous vraiment effacer le compte suivant? </h3>
        <div class="form">
         <div>
        <img src="$avatar" alt=""> 
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
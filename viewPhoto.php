<?php
include 'php/sessionManager.php';
include "models/photos.php";
include "models/users.php";

$temporaire= <<<HTML
<div class="loginForm"  style=  text-align: center;>
  
HTML;
$viewContent =$temporaire;
$userID="";
$errorPage = "errorPage";
if (isset($_GET["Id"]))
$id = $_GET["Id"];
else{
    //rediriger
}

$photoFile = PhotosFile();
$photo = PhotosFile()->get((int)$id);
if ($photo == null) {
redirect($errorPage);
}
$user = UsersFile()->get($photo->OwnerId());
if ($user == null) {
    redirect($errorPage);
    }
$avatar;

   
     $userID =$photo->OwnerId();
     
      
        $avatar = $user->avatar();
      
        $temporaire= <<<HTML
        <div class="photoImage" style="background-image:url('$avatar');"></div>
        <img class="photoImage" src="$avatar"  />
       HTML;
        $viewContent .= $user->name();
        $viewContent .= $temporaire;
      
      
     
     
     $viewContent .= $photo->Title(true);
     $image = $photo->Image(true);
     $temporaire= <<<HTML
      <div class="photoImage" style="background-image:url('$image');"></div>
  
   HTML;
    $viewContent .= $temporaire;
    $viewContent .= $photo->Description();
    $date = $photo->creationDate();
    $date =  date("Y-m-d H:i:s", $date);
    $temporaire= <<<HTML
    <div> <p> cette photo a été posté le " $date "<p><div>
   HTML;

    $viewContent .= $temporaire;
   
    

    $temporaire= <<<HTML
    <div>
      
    HTML;








include "views/master.php";
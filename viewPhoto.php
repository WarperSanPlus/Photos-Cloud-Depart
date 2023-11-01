<?php
include 'php/sessionManager.php';
include "models/photos.php";
include "models/users.php";
$viewContent = "";
$userID="";
$id  = $_GET["Id"];
$photoFile = PhotosFile();
$list = $photoFile->toArray();
$userFile = UsersFile();
$users = $userFile->toArray();
$avatar;
foreach ($list as $photo) {
    if ($photo->Id() == $id) {
     $userID =$photo->OwnerId();
     foreach($users as $user) {
      if ($user->Id() == $userID) {
        $avatar = $user->avatar();
      
        $temporaire= <<<HTML
        <div class="photoImage" style="background-image:url('$avatar');"></div>
          
       HTML;
        $viewContent .= $user->name();
        $viewContent .= $temporaire;
      }
      
     }
     
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
   
    }
}









include "views/master.php";
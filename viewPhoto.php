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
            <img class ='imageUploader'  src=$avatar alt="teste"/>
       HTML;
        $viewContent .= $user->name();
        $viewContent .= $temporaire;
      }
      
     }
     
 
    $viewContent .= $photo->render(true);
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
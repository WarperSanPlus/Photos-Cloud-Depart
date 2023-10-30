<?php
require "php/sessionManager.php";
require "models/users.php";

adminAccess();

if (isset($_POST["targetId"])) {
    $userFile = UsersFile();
    $user = $userFile->get((int) $_POST["targetId"]);
    $user->setType($_POST["nextAdmin"] ? 1 : 0);
    $userFile->update($user);
}

redirect("usersList.php");
<?php
include 'php/sessionManager.php';
include "models/photos.php";
include "models/users.php";



$viewName = "photoList";

userAccess();

$viewTitle = "Photos";

// --- Header Plus ---
$headerPlusAction = "newPhotoForm.php";
$headerPlusSubtitle = "Ajouter une photo";
// ---

$photoFile = PhotosFile();
$list = $photoFile->toArray();

$viewContent = "<div class='photosLayout'>";

$currentUserId = $_SESSION["currentUserId"];

// Charge et affiche les photos
$isAdmin = isset($_SESSION["isAdmin"]) ? $_SESSION["isAdmin"] : false;

if (isset($_GET["sort"])) {
    $sort = $_GET["sort"];

    // Sort by owners' name (UsersFile->sort)
    if (strcmp($sort, "owners") == 0) {
        $userFile = UsersFile(); // Get users
        $userFile->sort(); // Sort users by name
        $users = $userFile->toArray(); // Get users in array

        $sortedList = [];

        $count = 0;

        // Tests done with 13 users and 11 photos
        // Without $photosToRemove: 143 checks
        // With $photosToRemove: 87 checks

        // For each users, add their photos
        foreach ($users as $user) {
            $userId = $user->Id();

            $photosToRemove = [];

            for ($i = 0; $i < count($list); $i++) {
                $photo = $list[$i];

                $count++;

                if ($photo->OwnerId() != $userId)
                    continue;

                array_push($sortedList, $photo); // Add photo to temp list
                array_push($photosToRemove, $i); // Add index of the photo to remove
            }

            // Remove photo from list
            for ($i = count($photosToRemove) - 1; $i >= 0; $i--) {
                array_splice($list, $photosToRemove[$i], 1);
            }
        }
        //echo $count;
        $list = $sortedList; // Reassigned
    }
    // Sort by pictures' date (PhotoFile->sort)
    else if (strcmp($sort, "date") == 0) {
        $photoFile->sort();
        $list = $photoFile->toArray();
    }
    // Custom sorting
    else {
        $sortFunc = null;

        // Voir mes photos (current user)
        if (strcmp($sort, "mine") == 0) {
            $sortFunc = static function ($item) use ($currentUserId) {
                return $item->OwnerId() == $currentUserId;
            };
        }
        // Voir photos d'un user
        // mine + id of the user
        // if (str_starts_with($sort, "mine") && $isAdmin) {

        //     $sort = substr($sort, 4, strlen($sort) - 4);
        //     $sortId = ctype_digit($sort) ? (int)$sort : $currentUserId;

        //     $sortFunc = static function ($item) use ($sortId) {
        //         return $item->OwnerId() == $sortId;
        //     };
        // }
        else if (strcmp($sort, "shared") == 0 && $isAdmin) {
            $sortFunc = static function ($item) {
                return $item->Shared();
            };
        }
        else if (strcmp($sort, "privated") == 0 && $isAdmin) {
            $sortFunc = static function ($item) {
                return !$item->Shared();
            };
        }

        if ($sortFunc != null)
            $list = array_filter($list, $sortFunc);
    }
}

foreach ($list as $photo) {
    $viewContent .= $photo->render($isAdmin);
}

$viewContent = $viewContent . "</div>";

$viewScript = <<<HTML
    <script src='js/session.js'></script>
    <script defer>
        $("#addphotoCmd").hide();
    </script>
HTML;

include "views/master.php";

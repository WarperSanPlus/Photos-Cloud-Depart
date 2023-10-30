<?php
include 'php/sessionManager.php';
include 'php/formUtilities.php';
include 'models/users.php';

$id = 0;
$password = null;
$avatar = "images/no-avatar.png";
$userName = "";
function EmailExist($email)
{
    if (isset($email)) {
        $user = UsersFile()->findByKey("Email", $email);
        if ($user == null)
            return false;

        $GLOBALS["id"] = $user->Id();
        $GLOBALS["userName"] = $user->Name();
        $GLOBALS["avatar"] = $user->Avatar();
        $GLOBALS["password"] = $user->Password();
        $GLOBALS["isAdmin"] = $user->isAdmin();
        $GLOBALS["isBlocked"] = $user->isBlocked();

        return true;
    }
    return false;
}
function passwordOk($password)
{
    if (isset($password)) {
        return strcmp($password, $GLOBALS["password"]) === 0;
    }
    return false;
}

if (isset($_POST['submit'])) {
    $validUser = true;
    $_SESSION['Email'] = sanitizeString($_POST['Email']);

    if (!EmailExist($_SESSION['Email'])) {
        $validUser = false;
        $_SESSION['EmailError'] = 'Ce courriel n\'existe pas';
    }

    if ($validUser && $isBlocked) {
        $validUser = false;
        $_SESSION['annexError'] = 'Cet usager n\'a plus accès à ce service.<br>Merci de consulter un administrateur.';
    }

    if (!passwordOk(sanitizeString($_POST['Password']))) {
        $validUser = false;
        $_SESSION['passwordError'] = 'Mot de passe incorrect';
    }

    if ($validUser) {
        $_SESSION['validUser'] = true;
        $_SESSION['currentUserId'] = $id;
        $_SESSION['userName'] = $userName;
        $_SESSION['avatar'] = $avatar;
        $_SESSION['isAdmin'] = $isAdmin;
        redirect('photosList.php');
    }
}
redirect('loginForm.php');
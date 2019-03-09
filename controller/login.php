<?php
require_once("src/helper_functions.php");

// Unset all of the session variables.
$_SESSION = array();

//
// // If it's desired to kill the session, also delete the session cookie.
// // Note: This will destroy the session, and not just the session data!
// if (ini_get("session.use_cookies")) {
//     $params = session_get_cookie_params();
//     setcookie(session_name(), '', time() - 42000,
//         $params["path"], $params["domain"],
//         $params["secure"], $params["httponly"]
//     );
// }
//
// // Finally, destroy the session.
// session_destroy();

// Start new session
// if (session_status() < 2) {
//     $started = "yes";
// }

// Username from login form?
$user = (isset($_POST['user'])) ? esc($_POST['user']) : null;
$pass = (isset($_POST['pass'])) ? esc($_POST['pass']) : null;

// Username from registration form?
if (!$user) {
    $user = (isset($_GET['user'])) ? esc($_GET['user']) : null;
}

$msg = "";

if ($user && $pass) {
    $sql = "SELECT * FROM student WHERE username = ? OR email = ?;";
    $res = $db->executeFetch($sql, [$user, $user]);
    if ($res) {
        if (password_verify($pass, $res->password)) {
            $_SESSION["user"] = $user;
            if ($user == "admin") {
                header("Location: ?route=admin_calendar_1");
                exit();
            } else {
                // Active account?
                if ($res->status == 2) {
                    header("Location: ?route=student_calendar");
                    exit();
                } elseif ($res->status == 1) {
                    $msg = "La cuenta <b>{$user}</b> está pendiente de activación";
                } else {
                    $msg = "La cuenta <b>{$user}</b> ha sido desactivada";
                }
            }
        } else {
            $msg = "El usuario/email o contraseña son incorrectos";
        }
    } else {
        $msg = "El usuario/email o contraseña son incorrectos";
    }
}

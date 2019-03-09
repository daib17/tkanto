<?php
require_once("src/student_functions.php");

// Get login name from session
$student = (isset($_SESSION['user'])) ? $_SESSION['user'] : null;

$fnameError = $lnameError = $emailError = $phoneError = $passError = $newpassError = $newpass2Error = "";

$msg = "";
$isValid = true;
if (isset($_POST['button']) && $_POST['button'] == 'save') {
    // Validate input
    $fname = htmlspecialchars($_POST['fname']);
    $lname = htmlspecialchars($_POST['lname']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $pass = $_POST['pass'];
    $newpass = $_POST['newpass'];
    $newpass2 = $_POST['newpass2'];
    $uname = $student;

    if (!preg_match("/^[a-zA-Z ]*$/", $fname)) {
        $fnameError = "Solo números y letras permitidos";
        $isValid = false;
    }

    if (!preg_match("/^[a-zA-Z ]*$/", $lname)) {
        $lnameError = "Solo números y letras permitidos";
        $isValid = false;
    }

    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Formato no es válido";
        $isValid = false;
    }

    if (!filter_var($phone, FILTER_SANITIZE_NUMBER_INT)) {
        $phoneError = "Solo números y letras permitidos";
        $isValid = false;
    }

    if ($newpass != "" && strlen($newpass) < 6) {
        $newpassError = "Al menos 6 caracteres";
        $isValid = false;
    }

    if ($newpass2 != "" && strlen($newpass2) < 6) {
        $newpass2Error = "Al menos 6 caracteres";
        $isValid = false;
    }

    if ($isValid && $newpass != "" && !ctype_alnum($newpass)) {
        $newpassError = "Solo números y letras permitidos";
        $isValid = false;
    }

    if ($isValid && $newpass2 != "" && !ctype_alnum($newpass2)) {
        $newpass2Error = "Solo números y letras permitidos";
        $isValid = false;
    }

    $changePass = false;
    if ($isValid && ($newpass != "" || $newpass2 != "")) {
        if ($newpass != $newpass2) {
            $newpass2Error = "La contraseña y su confirmación no coinciden";
            $isValid = false;
        } else {
            $changePass = true;
        }
    }

    // Verify old password
    if ($changePass == true) {
        if ($pass == "") {
            $passError = "Introduce tu contraseña actual";
            $changePass = false;
        } else {
            $sql = "SELECT * FROM student WHERE username = ?;";
            $res = $db->executeFetch($sql, [$student]);
            if (!password_verify($pass, $res->password)) {
                $passError = "Contraseña incorrecta";
                $changePass = false;
            }
        }
    }

    // Check if new email already in use by another account
    if ($isValid) {
        $sql = "SELECT * FROM student WHERE email = ? AND username <> ?;";
        $res = $db->executeFetch($sql, [$email, $student]);
        if ($res) {
            $emailError = "El correo electrónico ya está registrado";
            $isValid = false;
        }
    }

    // Update database
    if ($isValid) {
        if ($changePass) {
            $hashed = password_hash($newpass, PASSWORD_DEFAULT);
            $sql = "UPDATE student SET firstname=?, lastname=?, email=?, phone=?, password=? WHERE username=?;";
            try {
                $db->execute($sql, [$fname, $lname, $email, $phone, $hashed, $student]);
                $msg = "La cuenta (+contraseña) han sido actualizada";
            } catch (Exception $ex) {
                $msg = "Error actualizando base de datos";
            }
        } else {
            $sql = "UPDATE student SET firstname=?, lastname=?, email=?, phone=? WHERE username=?;";
            try {
                $db->execute($sql, [$fname, $lname, $email, $phone, $student]);
                $msg = "La cuenta ha sido actualizada";
            } catch (Exception $ex) {
                $msg = "Error actualizando base de datos";
            }
        }
    }

} else {
    // Get student details from database
    $sql = "SELECT * FROM student WHERE username=?;";
    $res = $db->executeFetch($sql, [$student]);
    $fname = $lname = $email = $phone = $uname = $pass = $newpass = $newpass2 = "";
    if ($res) {
        $fname = $res->firstname;
        $lname = $res->lastname;
        $email = $res->email;
        $phone = $res->phone;
        $uname = $res->username;
    }
}

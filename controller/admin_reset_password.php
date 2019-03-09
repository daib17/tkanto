<?php

$studentID = (isset($_GET["id"])) ? $_GET["id"] : null;

$sql = "SELECT * FROM student WHERE id=?;";
$res = $db->executeFetch($sql, [$studentID]);

$msg = "";
if (isset($_POST["confirmBtn"])) {
    if ($_POST["confirmBtn"] == "yes") {
        $hashed = password_hash("123456", PASSWORD_DEFAULT);
        $sql = "UPDATE student SET password=? WHERE id=?;";
        try {
        $db->execute($sql, [$hashed, $studentID]);
        $msg = "La contraseña se ha reseteado";
        } catch (Exception $ex) {
            $msg = "No se ha podido resetear";
        }
    } else  {
        header("Location: ?route=admin_students_2&studentID={$studentID}");
    }
}

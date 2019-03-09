<?php
require_once("src/admin_functions.php");

if (isset($_POST['studentID'])) {
    $studentID = $_POST['studentID'];
} elseif (isset($_GET['studentID'])) {
    $studentID = $_GET['studentID'];
} else {
    $studentID = -1;
}

if (isset($_POST['button']) && $_POST['button'] == 'reset') {
    header("Location: ?route=admin_reset_password&id={$studentID}");

} elseif (isset($_POST['button']) && $_POST['button'] == 'save') {
    // Update database
    $id = htmlspecialchars($_POST['studentID']);
    $fname = htmlspecialchars($_POST['fname']);
    $lname = htmlspecialchars($_POST['lname']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $status = htmlspecialchars($_POST['status']);
    $sql = "UPDATE student SET firstname = ?, lastname = ?, email = ?, phone = ?, status = ? WHERE id = ?;";
    $db->execute($sql, [$fname, $lname, $email, $phone, $status, $id]);
    header("Location: ?route=admin_students_1");

} elseif (isset($_POST['button']) && $_POST['button'] == 'cancel') {
    header("Location: ?route=admin_students_1");

} else {
    // Get student details from database
    $student = getStudentByID($db, $studentID);
    // Generate select spinner
    $select = getSpinnerStatus($student->status);
}

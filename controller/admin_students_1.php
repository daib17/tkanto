<?php
require_once("src/admin_functions.php");

$filter = 2;
if (isset($_POST['filter'])) {
    $filter = $_POST['filter'];
} elseif (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
}
// $filter = $_POST['filter'] ?? $_GET['filter'];

$page = isset($_POST['page']) ? $_POST['page'] : 1;

if (isset($_POST['studentID'])) {
    $studentID = $_POST['studentID'];
    $infoMsg = "hidden";
} else {
    $studentID = -1;
    $editButton = "hidden";
}

if (isset($_POST['search'])) {
    $search = (strlen($_POST['search']) > 2) ? $_POST['search'] : "";
} else {
    $search = "";
}

// Spinner
$select = getSpinnerFilter($filter, $search);

// Generate student table
$studentTable = getStudentListAsTable($db, $filter, $page, $studentID, $search);

// pagination
$pagination = getPagination($db, $filter, $page, $search);

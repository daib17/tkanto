<?php
require_once("src/student_functions.php");

// Exception messages
$exception = "";

// Get login name from session
$student = (isset($_SESSION['user'])) ? $_SESSION['user'] : null;

// Actual page
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$page = is_numeric($page) ? $page : 1;

// Date and time from selected item
$selDate = isset($_POST['selDate']) ? $_POST['selDate'] : "";
$selTime = isset($_POST['selTime']) ? $_POST['selTime'] : "";

$selDate = strftime('%Y-%m-%e', strtotime($selDate));


// Default UI
$cancelButton = "hidden";

// Cancel booking?
if (isset($_POST['button']) && $_POST['button'] == "cancel") {
    try {
        cancelBooking($db, $selDate, $selTime, $student);
    } catch(Exception $ex) {
        $exception = $ex->getMessage();
    }
    $selDate = "";
    $selTime = "";
} elseif (isset($_POST['selDate']) && !isset($_POST['isCanceled'])) {
    $cancelButton = "";
}

// Generate student table
$bookingsTable = getBookingsList($db, $student, $page, $selDate, $selTime);

// Page navigation
$pagination = createPageNavigation($db, $student, $page);

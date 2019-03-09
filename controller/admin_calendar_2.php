<?php
require_once("src/admin_functions.php");

// Exception messages
$exception = "";

if (isset($_POST['selDate'])) {
    $date = esc($_POST['selDate']);
} elseif (isset($_GET['selDate'])) {
    $date = esc($_GET['selDate']);
}

// Prev and Next arrow buttons on header
if (isset($_POST['changeDate'])) {
    $date = new DateTime($date);
    if ($_POST['changeDate'] == ">>") {
        $date->modify("+1 day");
    } else {
        $date->modify("-1 day");
    }
    $date = $date->format("Y-m-d");
}

// Header labels
$dayOfWeek = strftime("%a", strtotime($date));
$dateSpa = strftime('%e-%m-%Y', strtotime($date));

// Selected hour label from table
if (isset($_POST['hourLabel']) && $_POST['hourLabel'] != "") {
    $hourStr = explode(" ", $_POST['hourLabel'])[0];     // hour
    $bookedBy = explode(" ", $_POST['hourLabel'])[1];    // name
} elseif (isset($_GET['hourLabel']) && $_GET['hourLabel'] != "") {
    $hourStr = explode(" ", $_GET['hourLabel'])[0];     // hour
    $bookedBy = explode(" ", $_GET['hourLabel'])[1];    // name
} else {
    $hourStr = "";
    $bookedBy = "";
}

// Toggle buttons visible/hidden flags in the UI
$copyBtn = "";
$cancelBtn = "hidden";
if (substr($bookedBy, -1) == "*") {
    $clearFlagBtn = "";
    $copyBtn = "hidden";
} else {
    $clearFlagBtn = "hidden";
    // Cancel button UI
    if ($bookedBy != "" && $bookedBy !="admin") {
        $cancelBtn = "";
        $copyBtn = "hidden";
    }
}

// Clicking on admin slot opens spinner with list of students
if ($bookedBy == "admin") {
    $studentSpinner = getStudentSpinner($db);
    $hideSpinner = "";
    $copyBtn = "hidden";
} else {
    $studentSpinner = "";
    $hideSpinner = "hidden";
}

// Generate array (0 to 27) of Hour objects for selected date from DB
$hourArr = generateHourArrayFromDB($db, $date);

// Copy template to next day?
if (isset($_POST['copyBtn'])) {
    try {
        copyTemplate($db, $date, $hourArr);
    } catch(Exception $ex) {
        $exception = $ex->getMessage();
    }
}

// Parameter sent by admin.js via URL parameters means spinner changed
if (isset($_GET['spinTime'])) {
    $spin = $_GET['spinTime'];
    if (is_numeric($spin) && in_array($spin, [0, 30, 60])) {
        $student = "admin";
        try {
            updateCalendarDB($db, $hourArr, $date, $student, $hourStr, $spin);
        } catch(Exception $ex) {
            print_r($date);
            print_r($ex->getMessage());
            exit;
            $exception = "Database operation failed.";
        }
    }
}

// Parameter sent by admin.js via URL parameters means spinner changed
if (isset($_GET['spinStudent'])) {
    $student = $_GET['spinStudent'];
    try {
        doBooking($db, $date, $hourStr, $student);
    } catch(Exception $ex) {
        $exception = $ex->getMessage();
    }
}

// Cancel booking
if (isset($_POST['cancelBtn'])) {
    try {
        cancelBooking($db, $date, $_POST['hourStr'], $_POST['cancelBtn']);
    } catch(Exception $ex) {
        $exception = $ex->getMessage();
    }
}

// Clear flag
if (isset($_POST['clearBtn'])) {
    try {
        clearFlag($db, $date, $_POST['hourStr']);
    } catch(Exception $ex) {
        $exception = $ex->getMessage();
    }
}

// Save notes
if (isset($_POST['saveBtn'])) {
    try {
        saveNotes($db, $date, $_POST['notes']);
    } catch(Exception $ex) {
        $exception = $ex->getMessage();
    }
}

// Get spinner for the selected hour
$timeSpinner = getSpinnerForSelectedHour($db, $hourArr, $hourStr);

// Get table
$hoursTable = getHoursTable($db, $date, $hourArr, $hourStr);

// Get notes
$notes = getNotes($db, $date);

<?php
require_once("src/admin_functions.php");

// selDate is the selected date in the calendar
// date is the month/year being show (day is irrelevant here)
$selDate = "";
if (isset($_POST['selDate'])) {
    $selDate = $_POST['selDate'];
    $date = $selDate;
} elseif (isset($_POST['date'])) {
    $date = $_POST['date'];
} else {
    $date = date('Y-m-d');   // today
}

// Calendar header
$headMonth = date("n", strtotime($date));
$headYear = date("Y", strtotime($date));

if (isset($_POST['changeMonth'])) {
    if ($_POST['changeMonth'] == ">>") {
        if ($headMonth == 12) {
            $headMonth = 1;
            $headYear++;
        } else {
            $headMonth++;
        }
    } else {
        if ($headMonth == 1) {
            $headMonth = 12;
            $headYear--;
        } else {
            $headMonth--;
        }
    }
    // Update month and year to show
    $date = $headYear . "-" . $headMonth . "-" . "01";
}

// Month name and year for table header
// setlocale(LC_ALL,"es_ES.UTF8");
$monthName = strftime("%B", strtotime($date));
$year = date("Y", strtotime($date));

// Generate calendar table
$calendarTable = getAdminMonthlyCalendar($db, $date, $selDate);

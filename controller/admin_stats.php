<?php
require_once("src/admin_functions.php");

$actualMonth = date('n');
$actualYear = date('o');

$fromDay = (isset($_POST["fromDay"])) ? $_POST["fromDay"] : 1;
$toDay = (isset($_POST["toDay"])) ? $_POST["toDay"] : 31;
$fromMonth = (isset($_POST["fromMonth"])) ? $_POST["fromMonth"] : $actualMonth;
$fromYear = (isset($_POST["fromYear"])) ? $_POST["fromYear"] : $actualYear;
$toMonth = (isset($_POST["toMonth"])) ? $_POST["toMonth"] : $actualMonth;
$toYear = (isset($_POST["toYear"])) ? $_POST["toYear"] : $actualYear;
$student = (isset($_POST["student"])) ? $_POST["student"] : "all";
$limit = (isset($_POST["limit"])) ? $_POST["limit"] : 10;

$selList = $selLimit20 = $selLimit30 = "";
if (isset($_POST["type"])) {
    $type = $_POST["type"];
    $selList = ($type == "list") ? "selected" : "";
}

if (isset($_POST["limit"])) {
    $selLimit20 = ($_POST["limit"] == 20) ? "selected" : "";
    $selLimit30 = ($_POST["limit"] == 30) ? "selected" : "";
}

$spinnerDayFrom = getDayListSpinner($fromDay);
$spinnerDayTo = getDayListSpinner($toDay);
$spinnerMonthFrom = getMonthListSpinner($fromMonth);
$spinnerMonthTo = getMonthListSpinner($toMonth);
$spinnerYearFrom = getYearListSpinner($db, $fromYear);
$spinnerYearTo = getYearListSpinner($db, $toYear);
$spinnerStudents = getStudentListSpinner($db, $student);


$fromDate = (new DateTime($fromYear . "-" . $fromMonth . "-" . $fromDay))->format("Y-m-d 00:00:00");
$toDate = (new DateTime($toYear . "-" . $toMonth . "-" . $toDay))->format("Y-m-d 23:59:59");

$table = "";
if (isset($_POST["button"]) && $_POST["button"] == "run") {
    if ($type == "acc") {
        $table = getStatAcc($db, $fromDate, $toDate, $student, $limit);
    } else {
        $table = getStatList($db, $fromDate, $toDate, $student, $limit);
    }
}

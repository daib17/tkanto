<?php

const ITEMS_PAGE = 5;

/**
* Generate monthly calendar for student.
*
* @param object $db database
* @param string $date actual (day)/month/year to show (day irrelevant here)
* @param string $selDate previously selected day
*
* @return string table
*/
function getMonthCalendar($db, $date, $selDate, $student)
{
    // This is the month/year to be show in the calendar
    // $day = date("j", strtotime($date));
    $month = date("n", strtotime($date));
    $year = date("Y", strtotime($date));

    // Day, month and year from a previously selected date
    $selDay = date("j", strtotime($selDate));
    $selMonth = date("n", strtotime($selDate));
    $selYear = date("Y", strtotime($selDate));

    // Today
    $dayToday = date("j");
    $monthToday = date("n");
    $yearToday = date("Y");

    // Get number of days in month
    $numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $table = "";
    // Get day of the week for the 1st of month/year
    $dayOne = date('w', strtotime($year . "-" . $month . "-" . "1"));

    // Get all entry for given month
    $dateFrom = $year . "-" . $month . "-01";
    $dateTo = $year . "-" . $month . "-" . $numDays;
    $sql = "SELECT * FROM calendar WHERE (student = ? OR student = ?) AND duration > ? AND date BETWEEN ? AND ?;";
    $res = $db->executeFetchAll($sql, [$student, "admin", 0, $dateFrom, $dateTo]);
    // Extract dates with booked and available times
    $bookedDates = [];
    $availDates = [];
    $canceledDates = [];
    foreach ($res as $booking) {
        if ($booking->student == "admin") {
            array_push($availDates, $booking->date);
        } elseif (!$booking->cancelby) {
            array_push($bookedDates, $booking->date);
        } else {
            array_push($canceledDates, $booking->date);
        }
    }

    // Empty cells before day one
    $emptyCells = $dayOne == 0 ? 6 : $dayOne - 1;
    for ($i = 1; $i < $emptyCells + 1; $i++) {
        if ($i == 1) {
            $table .= "<tr>";
        }
        $table .= "<td><input type='submit' class='button' name='day' value='' disabled /></td>";
    }

    // Cells for every day of the month
    for ($i = 1; $i < $numDays + 1; $i++) {
        $weekDay = date('w', strtotime($year . "-" . $month . "-" . $i));
        if ($weekDay == 1) {
            $table .= "<tr>";
        }
        $date = date('Y-m-d', strtotime($year . "-" . $month . "-" . $i));
        $selector = "";
        if ($i == $selDay && $month == $selMonth && $year == $selYear) {
            $selector = "selected ";
        }


        // One single book makes color green
        if (in_array($date, $bookedDates)) {
            $selector .= "booked";
        } elseif (in_array($date, $canceledDates)) {
            $selector .= "canceled ";
        } elseif ($weekDay == 6 || $weekDay == 0) {
            $selector .= "weekend";
        } else {
            $selector .= "normal";
        }

        if ($i == $dayToday && $month == $monthToday && $year = $yearToday) {
            $selector .= " bold";
        }

        if (in_array($date, $availDates)) {
            $asterisk = "*";
        } else {
            $asterisk = "";
        }

        $table .= "<td><div><form method='POST'><input type='hidden' name='hidePanel' value='A'><input type='hidden' name='route' value='student_calendar'><input type='hidden' name='selDate' value='{$date}'><input type='submit' class='button {$selector}' name='day' value='{$i}{$asterisk}'></form></div></td>";
        if ($weekDay == 0) {
            $table .= "</tr>";
        }
    }

    // Empty cells after last day of the month
    $weekDay = date('w', strtotime($year . "-" . $month . "-" . $numDays));
    if ($weekDay != 0) {
        for ($day = $weekDay; $day < 7; $day++) {
            $table .= "<td><input type='submit' class='button' name='day' value='' disabled /></td>";
        }
    }

    $table .= "</tr>";
    return $table;
}

/**
*
*/
function getDayCalendar($db, $student, $date, $selHour)
{
    $sql = "SELECT * FROM calendar WHERE date = ? AND (student = ? OR student = ?) AND duration > ? ORDER BY time;";
    $res = $db->executeFetchAll($sql, [$date, $student, "admin", 0]);

    // No results?
    $table = "";
    if (count($res) > 0) {
        // Generate table
        foreach ($res as $row) {
            $table .= "<tr>";
            $from = $row->time;
            if ($row->duration == 60) {
                $to = $from + 100;
            } else {
                $to = ($from % 100 == 0) ? $from + 30 : $from + 70;
            }
            if (strlen($from) == 3)
                $from = substr_replace($from, ':', 1, 0);
            else
                $from = substr_replace($from, ':', 2, 0);

            if (strlen($to) == 3)
                $to = substr_replace($to, ':', 1, 0);
            else
                $to = substr_replace($to, ':', 2, 0);

            // Labels
            $timeLabel = $from . " - " . $to;
            if ($row->duration == 30) {
                $timeLabel .= " *";
            }

            $selected = ($row->time == $selHour && !$row->cancelby) ? "selected" : "non-selected";

            $submit = "";
            if ($row->cancelby == null) {
                $submit="<input type='submit' class='button {$selected}' name='' value='{$timeLabel}'>";
            } else {
                $submit="<div class='non-button'>{$timeLabel}</div>";
            }

            // Show canceled only by admin and when no open alternative
            if ($row->cancelby) {
                $statusLabel = "cancelada por " . $row->cancelby;
                $color = "canceled";
            } else {
                $statusLabel = ($row->student == $student) ? "reservada" : "disponible";
                $color = ($row->student == $student) ? "booked" : "available";
            }

            // Time td
            $table .= "<td><form method='POST'><input type='hidden' name='route' value='student_calendar'><input type='hidden' name='hidePanel' value='A'><input type='hidden' name='selDate' value='{$date}'><input type='hidden' name='selHour' value={$row->time}><input type='hidden' name='statusLabel' value='{$statusLabel}'>{$submit}</form></td>";
            // Status td
            $table .= "<td class='{$color}'>{$statusLabel}</td>";
            $table .= "</tr>";
        }
    }

    // Empty res or only canceled bookings?
    if ($table == "") {
        $table .= "<tr>";
        $table .= "<td colspan=2 class='empty-cell'>No available times on this date</td>";
        $table .= "</tr>";
    }

    return $table;
}

/**
* Make reservation for student at date/time.
*/
function doBooking($db, $date, $time, $student) {
    try {
        $db->beginTransaction();
        // Get details for available hour from database
        $sql = "SELECT * FROM calendar WHERE date = ? AND time = ? AND student = ?;";
        $res = $db->executeFetch($sql, [$date, $time, "admin"]);
        if (!$res) {
            throw new Exception();
        }
        // Update first slot
        $now = date("Y-m-d H:i:s");
        $sql = "UPDATE calendar SET student = ?, bookdate = ? WHERE date = ? AND time = ? AND student = ?;";
        $db->execute($sql, [$student, $now, $date, $time, "admin"]);
        // Update second slot
        if ($res->duration == 60) {
            $time2 = ($time % 100 == 0) ? $time + 30 : $time + 70;
            $db->execute($sql, [$student, $now, $date, $time2, "admin"]);
        }
        $db->commit();
    } catch (Exception $ex) {
        $db->rollBack();
        throw new Exception("Error: La reserva ya no está disponible.");
    }
}

/**
* Cancel booking.
*/
function cancelBooking($db, $date, $time, $student) {
    try {
        $db->beginTransaction();
        // Get booking from db
        $sql = "SELECT * FROM calendar WHERE date = ? AND time = ? AND student = ? AND canceldate IS NULL;";
        $res = $db->executeFetch($sql, [$date, $time, $student]);
        if (!$res) {
            throw new Exception();
        }
        // Update first slot
        $now = date("Y-m-d H:i:s");
        $sql = "UPDATE calendar SET canceldate = ?, cancelby = ?, flag = ? WHERE date = ? AND time = ? AND student = ? AND canceldate IS NULL;";
        $db->execute($sql, [$now, $student, 1, $date, $time, $student]);
        // Update second slot
        if ($res->duration == 60) {
            $time2 = ($time % 100 == 0) ? $time + 30 : $time + 70;
            $db->execute($sql, [$now, $student, 1, $date, $time2, $student]);
        }
        $db->commit();
    } catch (Exception $ex) {
        $db->rollBack();
        throw new Exception("Error: operación cancelada.");
    }
}

/**
* Create bookings table for given student.
*/
function getBookingsList($db, $student, $page, $selDate, $selTime) {
    $items_page = ITEMS_PAGE;
    $offset = ($page - 1) * $items_page;
    $sql = "SELECT * FROM calendar WHERE student = ? AND date >= DATE(NOW()) AND duration > ? ORDER BY date, time LIMIT $items_page OFFSET $offset;";
    $res = $db->executeFetchAll($sql, [$student, 0]);

    // Get bookings from db
    $table = "";
    for ($i = 0; $i < $items_page; $i++) {
        if ($res && $i < count($res)) {
            // Date
            $date = $res[$i]->date;
            $time = $res[$i]->time;
            // Time
            if (strlen($res[$i]->time) == 3)
            $timeLabel = substr_replace($res[$i]->time, ':', 1, 0);
            else
            $timeLabel = substr_replace($res[$i]->time, ':', 2, 0);
            $durationLabel = ($res[$i]->duration == 30) ? "(30')" : "(60')";
            // Duration
            $duration = $res[$i]->duration;

            $selected = ($date == $selDate && $time == $selTime && !$res[$i]->cancelby) ? "selected" : "non-selected";

            $submit = "";
            $dateSpa = strftime('%e-%m-%Y', strtotime($date));
            if ($res[$i]->cancelby == null) {
                $submit="<input type='submit' class='{$selected}' name='selDate' value='{$dateSpa}'>";
            } else {
                $submit="<div class='non-button-left'>{$dateSpa}</div>";
            }

            // Add cancelation note
            $cancel = "";
            if ($res[$i]->cancelby) {
                $duration = "<span class='canceled'>Cancelada</span>";
                $cancel = "<input type='hidden' name='isCanceled' value='true'>";
            }

            $table .= "<tr>";
            $table .= "<td colspan=2><form method='POST'><input type='hidden' name='route' value='student_bookings'>{$cancel}<input type='hidden' name='selTime' value={$time}><input type='hidden' name='page' value={$page} />{$submit}</form></td>";
            $table .= "<td>{$timeLabel}</td>";
            $table .= "<td>{$duration}</td>";
            $table .= "</tr>";
        } else {
            // Empty row
            $table .= "<tr>";
            $table .= "<td colspan=2><div class='empty'>Empty</div></td>";
            $table .= "<td><div class='empty'>Empty</div></td>";
            $table .= "<td><div class='empty'>Empty</div></td>";
            $table .= "</tr>";
        }
    }
    return $table;
}

/**
* Create navigation for pagination.
*/
function createPageNavigation($db, $student, $actualPage) {
    $sql = "SELECT * FROM calendar WHERE student = ? AND date >= DATE(NOW()) AND duration > ?;";
    $res = $db->executeFetchAll($sql, [$student, 0]);
    if (!$res || count($res) < ITEMS_PAGE + 1) {
        return "";
    }

    $pages = ceil(count($res) / ITEMS_PAGE);
    // Pagination
    $table = "";
    $table .= "<nav>";
    $table .= "<ul class='pagination justify-content-center'>";

    for ($id = 1; $id < $pages + 1; $id++) {
        $active = ($id == $actualPage) ? "active" : "";
        $table .= "<form method='POST'><input type='hidden' name='route' value='student_bookings'><li class='page-item {$active}'><input type='submit' class='page-link' name='page' value={$id}></li></form>";
    }

    $table .= "</ul>";
    $table .= "</nav>";
    return $table;
}

/**
*
*/
function getRecentActivity($db, $student) {
    $sql = "(SELECT *, bookdate AS d, 'book' AS action FROM calendar WHERE student
    = ? AND duration > ?) UNION ALL (SELECT *, canceldate AS d, 'cancel' AS action FROM calendar WHERE student
    = ? AND duration > ?) ORDER BY d DESC LIMIT 15;";
    $res = $db->executeFetchAll($sql, [$student, 0, $student, 0]);
    $table = "";
    // Empty log
    if (!$res) {
        $table .= "<tr>";
        $table .= "<td colspan=3 class='empty-cell'>No hay actividad registrada</td>";
        $table .= "</tr>";
        return $table;
    }

    foreach ($res as $row) {
        $table .= "<tr>";
        // Hour
        $hour = sprintf("%04d", $row->time);
        $hour = substr_replace($hour, ":", 2, 0);
        $hour = ltrim($hour, "0");
        // Format dates
        $date = strftime('%e %h', strtotime($row->date));
        // Entries with no cancel date get null 'd' column after select
        if (!$row->d) {
            continue;
        }
        $action = ($row->action == "cancel") ? "cancel (" . $row->cancelby . ")" : "reserva";
        $booking =  $date . " (" . $hour . ")";
        $log = strftime('%e %h (%H:%M)', strtotime($row->d));

        $table .= "<td class='text'>$action</td>";
        $table .= "<td class='text'>$booking</td>";
        $table .= "<td class='text'>$log</td>";
        $table .= "</tr>";
    }

    return $table;
}

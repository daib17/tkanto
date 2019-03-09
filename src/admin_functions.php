<?php
const ITEMS_PAGE = 5;   // Number of students per page

/**
* Generate monthly calendar for admin.
*
* @param object $db database
* @param string $date actual (day)/month/year to show (day irrelevant here)
* @param string $selDate previously selected day
*
* @return string table
*/
function getAdminMonthlyCalendar($db, $date, $selDate)
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
    $sql = "SELECT * FROM calendar WHERE date BETWEEN ? AND ? AND cancelby IS NULL;";
    $res = $db->executeFetchAll($sql, [$dateFrom, $dateTo]);

    // Get free/booked hours for each day
    $free = [];
    $booked = [];
    for ($i = 0; $i < $numDays + 1; $i++) {
        $free[] = 0;
        $booked[] = 0;
    }

    foreach ($res as $aDay) {
        $dayNum = (int)substr($aDay->date, -2);
        if ($aDay->student == "admin" && $aDay->duration != 0) {
            $free[$dayNum]++;
        } elseif ($aDay->student != "admin" && $aDay->duration != 0) {
            $booked[$dayNum]++;
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
        $selector = "";
        if ($i == $selDay && $month == $selMonth && $year == $selYear) {
            $selector .= "selected ";
        }
        if ($weekDay == 6 || $weekDay == 0) {
            $selector .= "weekend";
        } else {
            $selector .= "empty";
        }

        if ($i == $dayToday && $month == $monthToday
        && $year = $yearToday) {
            $selector .= " bold";
        }
        $date = date('Y-m-d', strtotime($year . "-" . $month . "-" . $i));
        $freeEmpty = ($free[$i] == 0) ? "free-empty" : "";
        $bookedEmpty = ($booked[$i] == 0) ? "booked-empty" : "";

        $table .= "<td><form method='POST'><div class='day-label'><input type='hidden' name='route' value='admin_calendar_2'>
        <input type='hidden' name='selDate' value='{$date}'><input type='submit' class='button {$selector}' name='day' value={$i}><div class='free-mini {$freeEmpty}'>{$free[$i]}</div><div class='booked {$bookedEmpty}'>{$booked[$i]}</div></div></form></td>";
        if ($weekDay == 0) {
            $table .= "</tr>";
        }
    }

    // Empty cells after last day of the month
    $weekDay = date('w', strtotime($year . "-" . $month . "-" . $numDays));
    if ($weekDay != 0) {
        for ($i = $weekDay; $i < 7; $i++) {
            $table .= "<td><input type='submit' class='button' name='day' value='' disabled /></td>";
        }
    }

    $table .= "</tr>";
    return $table;
}



/**
*   Get students from database
*
*   @param int $status (0)disabled, (1)pending, (2)active, (3)all
*/
function getStudentsByStatus($db, $status = 2)
{
    if ($status == 3) {
        $sql = "SELECT * FROM student WHERE username NOT LIKE 'admin' ORDER BY lastname, firstname;";
        $res = $db->executeFetchAll($sql);
    } else {
        $sql = "SELECT * FROM student WHERE username NOT LIKE 'admin' AND status = ? ORDER BY lastname, firstname;";
        $res = $db->executeFetchAll($sql, [$status]);
    }
    return $res;
}


/**
*
*/
function getStudentByID($db, $id)
{
    $sql = "SELECT * FROM student WHERE id = ?";
    $res = $db->executeFetch($sql, [$id]);
    return $res;
}


/**
*
*/
function doSearch($db, $search)
{
    $search = "%" . $search . "%";
    $sql = "SELECT * FROM student WHERE username <> 'admin' AND (firstname LIKE ? OR lastname LIKE ?);";
    $res = $db->executeFetchAll($sql, [$search, $search]);
    return $res;
}

/**
*
*/
function getStudentListAsTable($db, $filterId = 3, $page, $selectedID, $search)
{
    $statusStr = ["Inactivo", "Espera", "Activo"];
    if ($search != "") {
        $res = doSearch($db, $search);
        $searchInput = "<input type='hidden' name='search' value='{$search}' />";
    } else {
        $res = getStudentsByStatus($db, $filterId);
        $searchInput = "";
    }

    $table = "";
    if ($res != null && count($res) > 0) {
        $firstID = ($page - 1) * ITEMS_PAGE; // id for first element to draw
        $lastID = $firstID + ITEMS_PAGE;
        for ($id = $firstID; $id < $lastID; $id++) {
            $table .= "<tr>";
            if ($id < count($res)) {
                $name = $res[$id]->firstname . "&nbsp;" . $res[$id]->lastname;
                $studentID = $res[$id]->id;
                if ($studentID == $selectedID) {
                    $selected = " selected";
                } else {
                    $selected = "";
                }

                $table .= "<td colspan=2><form method='POST'><input type='hidden' name='route' value='admin_students_1'>{$searchInput}<input type='submit' class='{$selected}' name='name' value='{$name}'><input type='hidden' name='studentID' value={$studentID} /><input type='hidden' name='filter' value={$filterId} /><input type='hidden' name='page' value={$page} /></form></td>";
                $table .= "<td>" . $statusStr[$res[$id]->status] . "</td>";
            } else {
                $table .= "<td colspan=2>&nbsp;</td>";
                $table .= "<td><div class='empty'>Empty</div></td>";
            }
            $table .= "</tr>";
        }
    } else {
        $table = "";
        $table .= "<tr>";
        $table .= "<td colspan=3 class='no-found-text'>" . "No students found in database" . "</td>";
        $table .= "</tr>";
    }

    return $table;
}



/**
*
*/
function getPagination($db, $filterId, $actualPage, $search)
{
    if ($search != "") {
        $res = doSearch($db, $search);
        $searchInput = "<input type='hidden' name='search' value='{$search}' />";
    } else {
        $res = getStudentsByStatus($db, $filterId);
        $searchInput = "";
    }

    if ($res == null || count($res) < ITEMS_PAGE + 1) {
        return "";
    }

    $pages = ceil((count($res) / ITEMS_PAGE));
    // Pagination
    $table = "";
    $table .= "<nav>";
    $table .= "<ul class='pagination justify-content-center'>";

    for ($id = 1; $id < $pages + 1; $id++) {
        $active = $id == $actualPage ? "active" : "";
        $table .= "<form method='POST'><input type='hidden' name='route' value='admin_students_1'>{$searchInput}<input type='hidden' name='filter' value={$filterId}/><li class='page-item {$active}'><input type='submit' class='page-link' name='page' value={$id}></li></form>";
    }

    $table .= "</ul>";
    $table .= "</nav>";
    return $table;
}



/**
*
*/
function getSpinnerFilter($status, $search) {
    $filterType = ["Inactivo", "Espera", "Activo", "Todos"];
    // Generate select spinner
    $select = "<select id='showFilter' class='form-control'>";
    foreach ($filterType as $key => $value) {
        if ($key == $status) {
            $select .= "<option value='{$key}' selected='selected'>" . $value . "</option>";
        } else {
            $select .= "<option value='{$key}'>{$value}</option>";
        }
    }

    if ($search != "") {
        $select .= "<option selected='selected'></option>";
    }

    $select .= "</select>";
    return $select;
}



/**
*
*/
function getSpinnerStatus($status) {
    $status;    // status 0 does not exist in panel B
    $filterType = ["Disabled", "Pending", "Active"];
    // Generate select spinner
    $select = "<select name='status' class='spinner-large form-control-lg w-50'>";
    foreach ($filterType as $key => $value) {
        if ($key == $status) {
            $select .= "<option value='{$key}'selected='selected'>" . $value . "</option>";
        } else {
            $select .= "<option value='{$key}'>{$value}</option>";
        }
    }
    $select .= "</select>";
    return $select;
}



/**
* Get hours from database for date and convert to array.
* Array with length 28 represents hours between 8 and 21:30
* Each array cell will contain the username that booked the time or
* admin if available.
*
* @return array contains student's usernames for booked time.
*/
function generateHourArrayFromDB($db, $date) {
    $hourArr = [];
    for ($i = 0; $i < 28; $i++) {
        $hourArr[] = new Hour();
    }
    $sql = "SELECT * FROM calendar WHERE date = ? AND (cancelby IS NULL OR flag = ?);";
    $res = $db->executeFetchAll($sql, [$date, 1]);
    foreach ($res as $row) {
        // Calculate id in array for time in db (800 is 0, 2130 is 27)
        $id = (((int)($row->time / 100)) - 8) * 2;
        if ($row->time % 100 != 0) {
            $id++;
        }
        $hourArr[$id]->setStudent($row->student);
        $hourArr[$id]->setTime($row->time);
        $hourArr[$id]->setDuration($row->duration);
        $hourArr[$id]->setUpdated($row->updated);
        $hourArr[$id]->setFlag($row->flag);
        $hourArr[$id]->setCancelBy($row->cancelby);
    }
    return $hourArr;
}



/**
* ADMIN - Planning
* Create dynamic spinner for selected date and hour
*
* @param db database
* @param array $arr array [0, 27] of Hour objects
* @param string hourStr selected "13:30"
*
* @return string html code for spinner
*/
function getSpinnerForSelectedHour($db, $arr, $hourStr) {
    // Convert string time to integer
    if ($hourStr != "") {
        $str = explode(":", $hourStr);
        $time = (int)$str[0] * 100 + (int)$str[1];
        // Get id in array for given hour
        $id = (((int)($time / 100)) - 8) * 2;
        if ($time % 100 != 0) {
            $id++;
        }
    }

    // Return if no hour has been selected yet or no admin time
    if ($hourStr == "") {
        $spinHTML = "<select id='timeSpinner' class='form-control' disabled>";
        $spinHTML .= "<option value='0'>0</option>";
        $spinHTML .= "</select>";
        return $spinHTML;
    }

    // Calculate id in array for time in db (800 is 0, 2130 is 27)
    $id = (((int)($time / 100)) - 8) * 2;
    if ($time % 100 != 0) {
        $id++;
    }

    $duration = $arr[$id]->getDuration();
    $student = $arr[$id]->getStudent();

    // Only admin and available hours are changeable
    if ($student != "admin" && $student != "") {
        $spinHTML = "<select id='timeSpinner' class='form-control' disabled>";
        $spinHTML .= "<option value=''>{$duration}</option>";
        $spinHTML .= "</select>";
        return $spinHTML;
    }

    $spinHTML = "<select id='timeSpinner' class='form-control'>";
    if ($duration == 0) {
        $spinHTML .= "<option value='0' selected>0</option>";
    } else {
        $spinHTML .= "<option value='0'>0</option>";
    }

    if ($duration == 30 ) {
        $spinHTML .= "<option value='30' selected>30</option>";
    } else {
        $spinHTML .= "<option value='30'>30</option>";
    }
    // Check if next slot is available
    if ($duration == 60) {
        $spinHTML .= "<option value='60' selected>60</option>";
    } elseif ($student == "admin") {

    }
    if ($id < 27) {
        if ($arr[$id + 1]->getDuration() == -1 || (
            $arr[$id + 1]->getStudent() == "admin" &&
            $arr[$id + 1]->getDuration() == 30
        )) {
            $spinHTML .= "<option value='60'>60</option>";
        }
    }

    $spinHTML .= "</select>";
    return $spinHTML;
}



/**
* Create spinner with list of students.

*/
function getStudentSpinner($db) {
    // Get all active students from database
    $sql = "SELECT * FROM student WHERE status LIKE ? AND username != ? ORDER BY lastname, firstname;";
    $res = $db->executeFetchAll($sql, [2, "admin"]);
    $spinHTML = "<select id='studentSpinner' class='form-control'>";
    $spinHTML .= "<option value='noStudent'>Reserva</option>";
    foreach ($res as $row) {
        $spinHTML .= "<option value='{$row->username}'>{$row->firstname} {$row->lastname}</option>";
    }
    $spinHTML .= "</select>";
    return $spinHTML;
}



/**
* Generate table from timetable with hours for given date.
*
* @return string html table
*/
function getHoursTable($db, $date, $hourArr, $hourLabel) {
    $table = "";
    $id = 0;
    for ($row = 0; $row < 7; $row++) {
        $table .= "<tr>";
        for ($id = $row * 4; $id < ($row * 4) + 4; $id++) {
            // Booked label showing only for bookings and canceled with flag
            $bookedBy = "";
            if (!$hourArr[$id]->getCancelBy() || $hourArr[$id]->getFlag() == 1) {
                $bookedBy = $hourArr[$id]->getStudent();
            }
            // Time label
            $hour = (int)($id / 2) + 8;
            $half = ($id % 2 == 1) ? ":30" : ":00";
            $time = $hour . $half;
            // Background color for cell
            $color = "";
            if ($hourArr[$id]->getDuration() == 0) {
                // Second 30 min slot for 60 min booking
                if ($hourArr[$id]->getFlag() == 1) {
                    $disabled = "flag-disabled";
                } elseif ($hourArr[$id - 1]->getStudent() == "admin") {
                    $disabled = "free-disabled";
                } else {
                    $disabled = "booked-disabled";
                }
                $table .= "<td><input id='h{$id}' type='submit' class='button {$disabled}' name='hourLabel' value='' /></td>";
            } else {
                if ($time == $hourLabel) {
                    $color = "selected ";
                }
                if ($bookedBy == "admin") {
                    $color .= "free";
                } elseif ($bookedBy != "" && $hourArr[$id]->getFlag() == 1) {
                    $bookedBy .= "*";
                    $color .= "flag";
                } elseif ($bookedBy != "" && $hourArr[$id]->getFlag() == 0) {
                    $color .= "booked";
                } else {
                    $color .= "empty";
                }
                $table .= "<td><input id='h{$id}' type='submit' class='button {$color}' name='hourLabel' value='{$time} {$bookedBy}' /></td>";
            }
        }
        $table .= "</tr>";
    }

    return $table;
}



/**
* Insert or update calendar database with new value from spinner.
*
* @return array updated $hoursArray
*/
function updateCalendarDB($db, $arr, $date, $student, $hourStr, $spin)
{
    // Convert hour "11:30" to integer 1130
    $val = explode(":", $hourStr);
    $time = $val[0] * 100 + $val[1];

    // Get array id for time (830 is 0)
    $id = (((int)($time / 100)) - 8) * 2;
    if ($time % 100 != 0) {
        $id++;
    }

    // New value same as old OR trying zero an already zero?
    if ($arr[$id]->getDuration() == $spin || ($arr[$id]->getDuration() == -1 && $spin == 0)) {
        return;
    }

    // From 0 to 30 or 60 (New entry in database)
    if ($arr[$id]->getStudent() == "") {
        $sql = "INSERT INTO calendar (date, student, time, duration) VALUES (?, ?, ?, ?);";
        $db->execute($sql, [$date, $student, $time, $spin]);
        // 60?
        if ($spin == 60) {
            $time = ($time % 100 == 0) ? $time += 30 : $time += 70;
            // Insert OR Update if second slot already booked by admin
            if ($arr[$id + 1]->getStudent() == "") {
                $sql = "INSERT INTO calendar (date, student, time, duration) VALUES (?, ?, ?, ?);";
                $db->execute($sql, [$date, $student, $time, 0]);
            } else {
                $sql = "UPDATE calendar SET duration = ? WHERE date = ? AND time = ?;";
                $db->execute($sql, [0, $date, $time]);
            }
        }
        // Redirect
        header("Location: ?route=admin_calendar_2&selDate=$date");
        exit;
    }

    // From 30/60 to 0 (update existing entry)
    if ($spin == 0) {
        // Previously 30
        if ($arr[$id]->getDuration() == 30) {
            $sql = "DELETE FROM calendar WHERE date = ? AND time = ? AND student = ?;";
            $db->execute($sql, [$date, $time, "admin"]);
        }
        // Previously 60
        if ($arr[$id]->getDuration() == 60) {
            // Delete first slot
            $sql = "DELETE FROM calendar WHERE date = ? AND time = ? AND student = ? AND duration = ?;";
            $db->execute($sql, [$date, $time, "admin", 60]);
            // Delete second slot
            $time2 = ($time % 100 == 0) ? $time += 30 : $time += 70;
            $sql = "DELETE FROM calendar WHERE date = ? AND time = ? AND student = ? AND duration = ?;";
            $db->execute($sql, [$date, $time2, "admin", 0]);
        }
    }

    // From 60 to 30 (update existing entry)
    if ($spin == 30) {
        // Previously 60
        if ($arr[$id]->getDuration() == 60) {
            // Update first slot
            $sql = "UPDATE calendar SET duration = ? WHERE date = ? AND time = ? AND student = ? AND duration = ?;";
            $db->execute($sql, [30, $date, $time, "admin", 60]);
            // Delete second slot
            $time2 = ($time % 100 == 0) ? $time += 30 : $time += 70;
            $sql = "DELETE FROM calendar WHERE date = ? AND time = ? AND student = ? AND duration = ?;";
            $db->execute($sql, [$date, $time2, "admin", 0]);
        }
    }

    // From 30 to 60 (update existing entry)
    if ($spin == 60) {
        // Update first slot
        $sql = "UPDATE calendar SET duration = ? WHERE date = ? AND time = ? AND student = ? AND duration = ?;";
        $db->execute($sql, [60, $date, $time, "admin", 30]);
        // Insert second slot
        $time2 = ($time % 100 == 0) ? $time += 30 : $time += 70;
        $sql = "INSERT INTO calendar (date, student, time, duration) VALUES (?, ?, ?, ?);";
        $db->execute($sql, [$date, $student, $time2, 0]);
    }

    // Redirect
    header("Location: ?route=admin_calendar_2&selDate=$date");
}



/**
* Make reservation for student at date/time.
*/
function doBooking($db, $date, $hourStr, $student) {
    try {
        $db->beginTransaction();
        // Convert hour "11:30" to integer 1130
        $val = explode(":", $hourStr);
        $time = $val[0] * 100 + $val[1];

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
        // Redirect
        header("Location: ?route=admin_calendar_2&selDate=$date");
    } catch (Exception $ex) {
        $db->rollBack();
        throw new Exception("Booking failed: time no longer available.");
    }
}



/**
* Cancel booking.
*
* @param string $orderedBy admin or student
*/
function cancelBooking($db, $date, $hourStr, $cancelBy) {
    try {
        $db->beginTransaction();
        // Convert hour "11:30" to integer 1130
        $val = explode(":", $hourStr);
        $time = $val[0] * 100 + $val[1];
        // Get booking from database
        $sql = "SELECT * FROM calendar WHERE date = ? AND time = ?;";
        $res = $db->executeFetch($sql, [$date, $time]);
        if (!$res) {
            throw new Exception();
        }

        $cancelBy = ($cancelBy != "admin") ? $res->student : "admin";
        // Update first 30 min slot
        $sql = "UPDATE calendar SET canceldate = NOW(), cancelby = ? WHERE date = ? AND time = ? AND cancelby IS NULL;";
        $db->execute($sql, [$cancelBy, $date, $time]);
        // Update second 30 min slot
        if ($res->duration == 60) {
            $time = ($time % 100 == 0) ? $time += 30 : $time += 70;
            $sql = "UPDATE calendar SET canceldate = NOW(), cancelby = ? WHERE date = ? AND time = ?;";
            $db->execute($sql, [$cancelBy, $date, $time]);
        }
        $db->commit();
        // Redirect
        header("Location: ?route=admin_calendar_2&selDate=$date");
    } catch (Exception $ex) {
        $db->rollBack();
        throw new Exception("Cancel operation failed.");
    }
}



/**
* Student already canceled, clear flag.
*/
function clearFlag($db, $date, $hourStr) {
    try {
        $db->beginTransaction();
        // Convert hour "11:30" to integer 1130
        $val = explode(":", $hourStr);
        $time = $val[0] * 100 + $val[1];
        // Get booking from database
        $sql = "SELECT * FROM calendar WHERE date = ? AND time = ? AND flag = ?;";
        $res = $db->executeFetch($sql, [$date, $time, 1]);
        if (!$res) {
            throw new Exception();
        }

        // 60 or 30 min?
        $sql = "UPDATE calendar SET flag = ? WHERE date = ? AND time = ? AND flag = ?;";
        $db->execute($sql, [0, $date, $time, 1]);
        // Second slot
        if ($res->duration == 60) {
            $time = ($time % 100 == 0) ? $time += 30 : $time += 70;
            $db->execute($sql, [0, $date, $time, 1]);
        }
        $db->commit();
        // Redirect
        header("Location: ?route=admin_calendar_2&selDate=$date");
    } catch (Exception $ex) {
        $db->rollBack();
        throw new Exception("Confirm operation failed.");
    }
}



/**
* Copy hours template
*/
function copyTemplate($db, $date, $arr)
{
    try {
        $db->beginTransaction();
        // Calculate date for one day later
        $nextDate = new DateTime($date);
        $nextDate->modify("+1 day");
        $nextDate = $nextDate->format("Y-m-d");
        // Copy
        foreach ($arr as $hour) {
            if ($hour->getStudent() == "admin") {
                if ($hour->getDuration() == 0) {
                    continue;
                }
                // 60?
                if ($hour->getDuration() == 60) {
                    $sql = "SELECT * FROM calendar WHERE date = ? AND time = ?;";
                    $time = $hour->getTime();
                    $res = $db->executeFetch($sql, [$nextDate, $time]);
                    $time2 = ($time % 100 == 0) ? $time + 30 : $time + 70;
                    $res2 = $db->executeFetch($sql, [$nextDate, $time2]);

                    if (!$res && !$res2) {
                        $sql = "INSERT INTO calendar (date, student, time, duration) VALUES (?, ?, ?, ?);";
                        $db->execute($sql, [$nextDate, "admin", $time, 60]);
                        $sql = "INSERT INTO calendar (date, student, time, duration) VALUES (?, ?, ?, ?);";
                        $db->execute($sql, [$nextDate, "admin", $time2, 0]);
                    }
                } else {
                    $sql = "SELECT * FROM calendar WHERE date = ? AND time = ?;";
                    $time = $hour->getTime();
                    $res = $db->executeFetch($sql, [$nextDate, $time]);
                    if (!$res) {
                        $sql = "INSERT INTO calendar (date, student, time, duration) VALUES (?, ?, ?, ?);";
                        $db->execute($sql, [$nextDate, "admin", $time, 30]);
                    }
                }
            }
        }
        $db->commit();
        // Redirect
        header("Location: ?route=admin_calendar_2&selDate=$date");
    } catch (Exception $ex) {
        $db->rollBack();
        throw new Exception("Copy template operation failed.");
    }
}



/**
* Log tab uses not params (only db).
*
* Stats tab uses fromDate, toDate, student and limit.
*/
function getRecentActivity($db, $fromDate = null, $toDate = null, $student = null, $limit = 10) {
    if ($fromDate == null) {
        // Log tab request
        $sql = "(SELECT *, bookdate AS d, 'book' AS action FROM calendar WHERE student != 'admin' AND duration > ?) UNION ALL (SELECT *, canceldate AS d, 'cancel' AS action FROM calendar WHERE student != 'admin' AND duration > ?) ORDER BY d DESC LIMIT {$limit};";
        $res = $db->executeFetchAll($sql, [0, 0]);
    } elseif ($student == null) {
        // Stats all students
        $sql = "(SELECT *, bookdate AS d, 'book' AS action FROM calendar WHERE student != 'admin' AND duration > ? AND bookdate >= ? AND bookdate <= ?) UNION ALL (SELECT *, canceldate AS d, 'cancel' AS action FROM calendar WHERE student != 'admin' AND duration > ? AND canceldate >= ? AND canceldate <= ?) ORDER BY d DESC LIMIT {$limit};";
        $res = $db->executeFetchAll($sql, [0, $fromDate, $toDate, 0, $fromDate, $toDate]);
    } else {
        // Stats one student
        $sql = "(SELECT *, bookdate AS d, 'book' AS action FROM calendar WHERE student = ? AND duration > ? AND bookdate >= ? AND bookdate <= ?) UNION ALL (SELECT *, canceldate AS d, 'cancel' AS action FROM calendar WHERE student = ? AND duration > ? AND canceldate >= ? AND canceldate <= ?) ORDER BY d DESC LIMIT {$limit};";
        $res = $db->executeFetchAll($sql, [$student, 0, $fromDate, $toDate, $student, 0, $fromDate, $toDate]);
    }

    $table = "";
    // Empty log
    if (!$res) {
        $table .= "<tr>";
        $table .= "<td colspan=4 class='empty-cell'>No hay actividad registrada</td>";
        $table .= "</tr>";
        return $table;
    }

    foreach ($res as $row) {
        $table .= "<tr>";
        // From
        $from = sprintf("%04d", $row->time);
        $from = substr_replace($from, ":", 2, 0);
        $from = ltrim($from, "0");
        $timeLabel = $from;
        // Format dates
        $date = strftime('%e %h', strtotime($row->date));
        // Entries with no cancel date get null 'd' column after select
        if (!$row->d) {
            continue;
        }

        $action = ($row->action == "cancel") ? "cancel (" . $row->cancelby . ")" : "reserva";
        $booking =  $date . " (" . $timeLabel . ")";
        $log = strftime('%e %h (%H:%M)', strtotime($row->d));

        $table .= "<td class='text'>$row->student</td>";
        $table .= "<td class='text'>$action</td>";
        $table .= "<td class='text'>$booking</td>";
        $table .= "<td class='text'>$log</td>";
        $table .= "</tr>";
    }

    return $table;
}


/**
* Get notes
*/
function getNotes($db, $date) {
    $notes = "";
    $sql = "SELECT * FROM note WHERE date=?;";
    $res = $db->executeFetch($sql, [$date]);
    if ($res) {
        $notes = $res->text;
    }
    return $notes;
}


/**
* Save notes
*/
function saveNotes($db, $date, $text) {
    try {
        $sql = "SELECT * FROM note WHERE date=?;";
        $res = $db->executeFetch($sql, [$date]);
        if ($res) {
            $sql = "UPDATE note SET text = ? WHERE date = ?;";
            $db->execute($sql, [$text, $date]);
        } else {
            $sql = "INSERT INTO note (date, text) VALUES (?, ?);";
            $db->execute($sql, [$date, $text]);
        }
    } catch (Exception $ex) {
        throw new Exception("Database update failed.");
    }
}


/**
* Generate spinner 31 days
*/
function getDayListSpinner($selDay) {
    $options = "";
    for ($i = 1; $i < 32; $i++) {
        $selected = ($i == $selDay) ? "selected" : "";
        $options .= "<option value='{$i}' {$selected}>$i</option>";
    }
    return $options;
}


/**
* Generate spinner 12 months
*/
function getMonthListSpinner($selMonth) {
    $months = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
    $options = "";
    for ($i = 0; $i < 12; $i++) {
        $value = $i + 1;
        $selected = ($value == $selMonth) ? "selected" : "";
        $options .= "<option value='{$value}' {$selected}>$months[$i]</option>";
    }
    return $options;
}


/**
* Get spinner list of years
*/
function getYearListSpinner($db, $selYear) {
    $options = "";
    for ($i = 2018; $i < 2029; $i++) {
        $selected = ($i == $selYear) ? "selected" : "";
        $options .= "<option value='{$i}' {$selected}>{$i}</option>";
    }
    return $options;
}


/**
* Get spinner list of active students
*/
function getStudentListSpinner($db, $selStudent) {
    $sql = "SELECT * FROM student WHERE status=? AND username <> ?;";
    $res = $db->executeFetchAll($sql, [2, "admin"]);
    $options = "<option value='0'>Todos</options>";
    foreach ($res as $row) {
        $selected = ($row->id == $selStudent) ? "selected" : "";
        $options .= "<option value='{$row->id}' {$selected}>{$row->firstname} {$row->lastname} ({$row->username})</option>";
    }
    return $options;
}


/**
* Get table as accumulated stats
*/
function getStatAcc($db, $fromDate, $toDate, $student, $limit) {
    $table = "<table class='table table-bordered table-selectable stats'>
    <thead>
    <tr>
    <th scope='col'>Alumno</th>
    <th scope='col'>Reservas</th>
    <th scope='col'>Cancel</th>
    </tr>
    </thead>
    <tbody>";

    if ($student == 0) {
        $sql = "SELECT student,
        SUM(CASE WHEN (bookdate >= ? AND bookdate <= ? AND duration > ?) THEN 1 ELSE 0 END) booked,
        SUM(CASE WHEN (canceldate >= ? AND canceldate <= ? AND duration > ?) THEN 1 ELSE 0 END) canceled
        FROM calendar WHERE student <> ?
        GROUP BY student ORDER BY student LIMIT {$limit};";
        $res = $db->executeFetchAll($sql, [$fromDate, $toDate, 0, $fromDate, $toDate, 0, "admin"]);

    } else {
        // Get username
        $sql = "SELECT * FROM student WHERE id=?;";
        $res = $db->executeFetch($sql, [$student]);
        $uname = $res->username;
        // Get all entries for username
        $sql = "SELECT student,
        SUM(CASE WHEN (bookdate >= ? AND bookdate <= ? AND duration > ?) THEN 1 ELSE 0 END) booked,
        SUM(CASE WHEN (canceldate >= ? AND canceldate <= ? AND duration > ?) THEN 1 ELSE 0 END) canceled
        FROM calendar WHERE student = ?
        GROUP BY student ORDER BY student LIMIT {$limit};";
        $res = $db->executeFetchAll($sql, [$fromDate, $toDate, 0, $fromDate, $toDate, 0, $uname]);
    }

    // Empty log
    if (!$res) {
        $table .= "<tr>";
        $table .= "<td colspan=3 class='empty-cell'>Log is empty</td>";
        $table .= "</tr>";
        $table .= "</tbody></table>";
        return $table;
    }

    foreach ($res as $row) {
        $table .= "<tr>";
        $table .= "<td class='text'>$row->student</td>";
        $table .= "<td class='text'>$row->booked</td>";
        $table .= "<td class='text'>$row->canceled</td>";
        $table .= "</tr>";
    }

    $table .= "</tbody></table>";
    return $table;
}


/**
* Get table as list stats
*/
function getStatList($db, $fromDate, $toDate, $student, $limit) {
    $table = "<table class='table table-bordered table-selectable stats'>
    <thead>
    <tr>
    <th scope='col'>Alumno</th>
    <th scope='col'>Actividad</th>
    <th scope='col'>Reserva</th>
    <th scope='col'>Registro</th>
    </tr>
    </thead>
    <tbody>";

    if ($student == 0) {
        $table .= getRecentActivity($db, $fromDate, $toDate, null, $limit);
    } else {
        // Get username
        $sql = "SELECT * FROM student WHERE id=? LIMIT {$limit};";
        $res = $db->executeFetch($sql, [$student]);
        $table .= getRecentActivity($db, $fromDate, $toDate, $res->username, $limit);
    }

    $table .= "</tbody></table>";
    return $table;
}


/**
* Reset password (with random) for student with given email.
*/
function resetStudentPassword($db, $email) {
    // Generate random password
    $char = "123456789abcdefghijkmnprstuvwxyzABCDEFGHJKLMNPRSTVWXYZ";
    $str = "";
    for ($i = 0; $i < 6; $i++) {
        $str .= $char[rand(0, strlen($char) - 1)];
    }

    // Hash and update
    $password = password_hash($str, PASSWORD_DEFAULT);
    try {
        $sql = "UPDATE student SET password=? WHERE email=?;";
        $db->execute($sql, [$password, $email]);
    } catch (Exception $ex) {
        throw new Exception("Database update failed.");
    }

    return $str;
}

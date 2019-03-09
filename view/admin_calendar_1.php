<div class="container main-container">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="?route=admin_calendar_1">Calendario</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?route=admin_students_1"><i class="fas fa-users"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?route=admin_recent"><i class="fas fa-database"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?route=admin_stats"><i class="fas fa-chart-bar"></i></a>
        </li>
    </ul>
    <div class="container main-container-inner">
        <div class="admin-calendar">
            <table class="table table-only-header table-bordered">
                <thead>
                    <form class="calendar-form" method="POST">
                        <input type="hidden" name="route" value="admin_calendar_1">
                        <input type="hidden" name="date" value=<?= $date ?>>
                        <tr>
                            <th scope="col" colspan="1">
                                <input type="submit" class="btn btn-arrow btn-block font-weight-bold" name="changeMonth" value="<<">
                            </th>
                            <th scope="col" colspan="5" class="title"><?= $monthName ?> <?= $year ?></th>
                            <th scope="col" colspan="1">
                                <input type="submit" class="btn btn-arrow btn-block font-weight-bold" name="changeMonth" value=">>">
                            </th>
                        </tr>
                    </form>
                </thead>
            </table>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="day-label">Lun</th>
                        <th class="day-label">Mar</th>
                        <th class="day-label">Mie</th>
                        <th class="day-label">Jue</th>
                        <th class="day-label">Vie</th>
                        <th class="day-label">Sab</th>
                        <th class="day-label">Dom</th>
                    </tr>
                </thead>
                <tbody>
                    <?= $calendarTable ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/admin.js"></script>

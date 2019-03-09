<div class="container main-container">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="?route=student_calendar">Calendario</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?route=student_bookings"><i class="fas fa-list"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?route=student_recent"><i class="fas fa-database"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?route=student_account"><i class="fas fa-user"></i></a>
        </li>
    </ul>

    <div class="container main-container-inner">
        <div class="student-calendar <?= $hidePanelA ?>">
            <h5 class="mb-4">
                Selecciona una fecha
            </h5>
            <table class="table table-only-header table-bordered">
                <form class="calendar-form" method="POST">
                    <input type="hidden" name="route" value="student_calendar">
                    <input type="hidden" name="date" value=<?= $date ?>>
                    <thead>
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
            <div>
                (*) Fechas con horas disponibles
            </div>
        </div>
        <div class="student-day <?= $hidePanelB ?>">
            <h5 class="mb-4">Selecciona hora para reservar/cancelar</h5>
            <?php if ($exception != ""): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $exception ?>
                </div>
            <?php endif; ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col" colspan="2"><?= $daySel ?> <?= $monthSel ?> <?= $yearSel ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?= $dayTable ?>
                </tbody>
            </table>
            <div>
                (*) Clases de 30 minutos
            </div>
        </div>

        <form class="<?= $hidePanelB ?>" method="POST">
            <input type="hidden" name="route" value="student_calendar">
            <input type="hidden" name="hidePanel" value="A">
            <input type="hidden" name="selDate" value="<?= $selDate ?>">
            <input type="hidden" name="selHour" value="<?= $selHour ?>">
            <button class="btn btn-lg <?= $buttonType ?> btn-block font-weight-bold mt-4" type="submit" name="button" value="<?= $buttonLabel ?>"><?= $buttonLabel ?></button>
        </form>
        <form class="<?= $hidePanelB ?>" method="POST">
            <input type="hidden" name="route" value="student_calendar">
            <input type="hidden" name="selDate" value="<?= $selDate ?>">
            <input type="hidden" name="hidePanel" value="B">
            <button class="btn btn-lg btn-secondary btn-block font-weight-bold mt-4" type="submit" name="button" value="back">Ir a calendario</button>
        </form>
    </div>
</div>

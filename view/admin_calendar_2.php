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
        <?php if ($exception != ""): ?>
            <div class="alert alert-danger" role="alert">
                <?= $exception ?>
            </div>
        <?php endif; ?>
        <div class="spinner">
            <div class="float-left w-25 mb-4">
                <?= $timeSpinner ?>
            </div>
            <div class="float-right w-50 mb-4 <?= $hideSpinner ?>">
                <?= $studentSpinner ?>
            </div>
            <div class="float-right mb-4">
                <form method="POST">
                    <input type="hidden" name="route" value="admin_calendar_2">
                    <input type="hidden" name="selDate" value="<?= $date ?>">
                    <input type="hidden" name="hourStr" value=<?= $hourStr ?>>
                    <button class="btn btn-md btn-info font-weight-bold <?= $copyBtn ?>" type="submit" name="copyBtn" value="copy">Copiar plantilla >></button>
                    <button class="btn btn-md btn-warning font-weight-bold  <?= $clearFlagBtn ?>" type="submit" name="clearBtn" value="admin">Confirmar cancelación</button>
                    <button class="btn btn-md btn-danger font-weight-bold  <?= $cancelBtn ?>" type="submit" name="cancelBtn" value="admin">Yo cancelo</button>
                    <button class="btn btn-md btn-danger font-weight-bold <?= $cancelBtn ?>" type="submit" name="cancelBtn" value="student">Alumno</button>
                </form>
            </div>
        </div>
        <div class="admin-calendar">
            <form class="calendar-form" method="POST">
                <input type="hidden" name="route" value="admin_calendar_2">
                <input type="hidden" name="selDate" value="<?= $date ?>">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" colspan="1">
                                <input type="submit" class="btn btn-arrow btn-block font-weight-bold" name="changeDate" value="<<">
                            </th>
                            <th id="headerLabel" scope="col" colspan="2" class="title"><?= $dateSpa ?> / <?= $dayOfWeek ?></th>
                            <th scope="col" colspan="1">
                                <input type="submit" class="btn btn-arrow btn-block font-weight-bold" name="changeDate" value=">>">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?= $hoursTable ?>
                    </tbody>
                </table>
            </form>
            <form method="POST">
                <input type="hidden" name="route" value="admin_calendar_1">
                <input type="hidden" name="selDate" value="<?= $date ?>">
                <button class="float-right btn btn-md btn-secondary btn-block font-weight-bold my-4 w-25" type="submit">Volver</button>
            </form>
            <form method="POST">
                <input type="hidden" name="route" value="admin_calendar_2">
                <input type="hidden" name="selDate" value="<?= $date ?>">
                <button class="float-left btn btn-md btn-danger btn-block font-weight-bold my-4 w-50" type="submit" name="saveBtn">Guardar notas</button>
                <div class="form-group clearfix">
                    <textarea id="text-area" name="notes" rows="6"><?= $notes ?></textarea>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/admin.js"></script>

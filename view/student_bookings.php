<div class="container main-container">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" href="?route=student_calendar"><i class="far fa-calendar-alt"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="?route=student_bookings">Clases</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?route=student_recent"><i class="fas fa-database"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?route=student_account"><i class="fas fa-user"></i></a>
        </li>
    </ul>

    <div class="container main-container-inner">
        <h5 class="mb-4">Clases reservadas</h5>
        <table class="table table-bordered table-selectable">
            <thead>
                <tr>
                    <th scope="col" colspan="2">Fecha</th>
                    <th scope="col">Hora</th>
                    <th scope="col">Duración</th>
                </tr>
            </thead>
            <tbody>
                <?= $bookingsTable ?>
            </tbody>
        </table>
        <?= $pagination ?>

        <form class="top-buffer" method="POST">
            <input type='hidden' name='route' value='student_bookings'>
            <input type="hidden" name="selDate" value="<?= $selDate ?>">
            <input type="hidden" name="selTime" value="<?= $selTime ?>">
            <button class="btn btn-lg btn-danger btn-block font-weight-bold <?= $cancelButton ?>" type="submit" name="button" value="cancel">Cancelar</button>
        </form>
        <form method="POST">
            <input type='hidden' name='route' value='student_calendar'>
            <button class="btn btn-lg btn-secondary btn-block font-weight-bold mt-4" type="submit">Ir a calendario</button>
        </form>
    </div>
</div>

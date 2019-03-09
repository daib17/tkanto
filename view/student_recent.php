<div class="container main-container">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" href="?route=student_calendar"><i class="far fa-calendar-alt"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?route=student_bookings"><i class="fas fa-list"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="?route=student_recent">Registro</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?route=student_account"><i class="fas fa-user"></i></a>
        </li>
    </ul>

    <div class="container main-container-inner">
        <h5 class="mb-4">Actividad reciente</h5>
        <table class="table table-bordered table-selectable">
            <thead>
                <tr>
                    <th scope="col">Actividad</th>
                    <th scope="col">Reserva</th>
                    <th scope="col">Registro</th>
                </tr>
            </thead>
            <tbody>
                <?= $logTable ?>
            </tbody>
        </table>
    </div>
</div>

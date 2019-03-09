<div class="container main-container">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" href="?route=admin_calendar_1"><i class="far fa-calendar-alt"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?route=admin_students_1"><i class="fas fa-users"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="?route=admin_recent"><span class="fa-text">Registro</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?route=admin_stats"><i class="fas fa-chart-bar"></i></a>
        </li>
    </ul>
    </ul>
    <div class="container main-container-inner">
        <table class="table table-bordered table-selectable">
            <thead>
                <tr>
                    <th scope="col">Alumno</th>
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

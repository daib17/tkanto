<div class="container main-container">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" href="?route=admin_calendar_1"><i class="far fa-calendar-alt"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="?route=admin_students_1"><span class="fa-text"> Alumnos</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?route=admin_recent"><i class="fas fa-database"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?route=admin_stats"><i class="fas fa-chart-bar"></i></a>
        </li>
    </ul>
    <div class="container main-container-inner">
        <div class="edit-content col-12 col-lg-8 offset-lg-2">
            <!-- <h4 class="float-left">Datos</h4> -->
            <form method="POST">
                <button class="float-right btn btn-md btn-danger btn-block font-weight-bold w-50 mb-4" type="submit" name="button" value="reset">Reset Contraseña</button>
                <input type="hidden" name="route" value="admin_students_2">
                <input type="hidden" name="studentID" value="<?= $studentID ?>">
                <div class="form-group">
                    <input type="text" name="fname" class="form-control-lg w-100" placeholder="Nombre" value="<?= $student->firstname ?>" required>
                </div>
                <div class="form-group">
                    <input type="text" name="lname" class="form-control-lg w-100 mt-2" placeholder="Apellido" value="<?= $student->lastname ?>" required>
                </div>
                <div class="form-group">
                    <input type="text" name="email" class="form-control-lg w-100" placeholder="Correo electrónico" value="<?= $student->email ?>" required>
                </div>
                <div class="form-group">
                    <input type="number" name="phone" class="form-control-lg w-100" placeholder="Número de móvil" value="<?= $student->phone ?>" required>
                </div>
                <div class="form-group">
                    <input type="text" name="username" class="form-control-lg w-100" placeholder="Username" value="<?= $student->username ?>" disabled>
                </div>
                <div class="form-group">
                    <?= $select ?>
                </div>
                <div class="mt-5">
                    <button class="btn btn-lg btn-info btn-block w-100 font-weight-bold" type="submit" name="button" value="save">Guardar</button>
                </div>
            </form>
            <form method="POST">
                <div class="my-4">
                    <input type="hidden" name="route" value="admin_students_2">
                    <button class="btn btn-lg btn-secondary btn-block w-100 font-weight-bold" type="submit" name="button" value="cancel">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

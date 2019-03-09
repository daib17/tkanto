<div class="container main-container">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" href="?route=student_calendar"><i class="far fa-calendar-alt"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?route=student_bookings"><i class="fas fa-list"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?route=student_recent"><i class="fas fa-database"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="?route=student_account">Mi cuenta</a>
        </li>
    </ul>
    <div class="container main-container-inner">
        <div class="edit-content col-12 col-lg-8 offset-lg-2">
            <h5>Nombre de usuario: <b><?= $student ?></b></h5>
            <br>
            <?php if ($msg != ""): ?>
                <div class="alert alert-info" role="alert">
                    <?= $msg ?>
                </div>
            <?php endif; ?>
            <form method="POST">
                <input type="hidden" name="route" value="student_account">
                <div class="form-group">
                    <input type="text" class="form-control-lg mt-2 w-100" placeholder="Nombre" name="fname" value="<?= $fname ?>" maxlength="25" required>
                    <small class="text-left form-text error-message"><?= $fnameError ?></small>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control-lg mt-2 w-100" placeholder="Apellido" name="lname" value="<?= $lname ?>" maxlength="25" required>
                    <small class="text-left form-text error-message"><?= $lnameError ?></small>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control-lg mt-2 w-100" placeholder="Correo electrónico" name="email" value="<?= $email ?>" required>
                    <small class=" text-left form-text error-message"><?= $emailError ?></small>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control-lg mt-2 w-100" placeholder="Número de móvil" name="phone" value="<?= $phone ?>" maxlength="15" required>
                    <small class="text-left form-text error-message"><?= $phoneError ?></small>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control-lg mt-2 w-100" placeholder="Contraseña" name="pass" value="<?= $pass ?>" maxlength="15">
                    <small class="text-left form-text error-message"><?= $passError ?></small>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control-lg mt-2 w-100" placeholder="Nueva contraseña" name="newpass" value="<?= $newpass ?>" maxlength="15">
                    <small class="text-left form-text error-message"><?= $newpassError ?></small>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control-lg mt-2 w-100" placeholder="Confirma nueva contraseña" name="newpass2" value="<?= $newpass2 ?>" maxlength="15">
                    <small class="text-left form-text error-message"><?= $newpass2Error ?></small>
                </div>
                <div class="mt-5">
                    <button class="btn btn-lg btn-info btn-block w-100 font-weight-bold" type="submit" name="button" value="save">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

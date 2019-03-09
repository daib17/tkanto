<div class="main-container">
    <div class="register-content text-center col-10 offset-1 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-xl-4 offset-xl-4">
        <h3>Introduce tus datos</h3>
        <br>
        <form method="POST">
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
                <input type="password" name="pass" class="form-control-lg mt-2 w-100" placeholder="Contraseña" name="password" value="<?= $pass ?>" maxlength="15" required>
                <small class="text-left form-text error-message"><?= $passError ?></small>
            </div>
            <div class="form-group">
                <input type="password" class="form-control-lg mt-2 w-100" placeholder="Confirmar contraseña" name="pass2" value="<?= $pass2 ?>" maxlength="15" required>
                <small class="text-left form-text error-message"><?= $pass2Error ?></small>
            </div>
            <?php if (!$isValid): ?>
                <button class="btn btn-lg btn-info btn-block font-weight-bold mt-5 mb-3" type="submit" name="registerBtn" value="register">Registrar</button>
            <?php endif; ?>
        </form>
        <?php if ($msg != ""): ?>
            <div class="alert alert-danger mt-3">
                <?= $msg ?>
            </div>
        <?php endif; ?>
        <?php if ($isValid): ?>
            <form>
                <input type="hidden" name="route" value="login">
                <input type="hidden" name="user" value="<?= $uname ?>">
                <div class="alert alert-success mt-3">
                    Tu nombre de usuario es: <b><?= $uname ?></b>
                </div>
                <button class="btn btn-lg btn-secondary btn-block font-weight-bold mt-4 mb-3" type="submit">Ir a login</button>
            </form>
        <?php else: ?>
            <form>
                <input type="hidden" name="route" value="login">
                <button class="btn btn-lg btn-light btn-block mt-5 mb-3" type="submit">No necesito registrarme</button>
            </form>
        <?php endif; ?>
    </div>
</div>

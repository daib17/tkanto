<div class="main-container">
    <div class="col-10 offset-1 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-xl-4 offset-xl-4">
        <br><br>
        <h3>Reset contraseña</h3><br>
        Alumno/a: <b><?= $res->firstname ?> <?= $res->lastname?></b>
        <br>
        Nombre de usuario: <b><?= $res->username ?></b>
        <br><br>
        Nueva contraseña: <b>123456</b>
        <br><br>
        <?php if ($msg != ""): ?>
            <div class="alert alert-info" role="alert">
                <?= $msg ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <button class="btn btn-lg btn-danger btn-block font-weight-bold mt-4 mb-3" type="submit" name="confirmBtn" value="yes">Sí, resetea contraseña</button>
            <button class="btn btn-lg btn-secondary btn-block font-weight-bold mt-4 mb-3" type="submit" name="confirmBtn" value="no">Cancelar</button>
        </form>
    </div>
</div>

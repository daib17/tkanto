<div class="main-container">
    <div class="col-10 offset-1 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-xl-4 offset-xl-4">
        <br><br>
        <h3>Recuperar contraseña</h3>
        <br>
        <h5>Introduce to correo electrónico</h5>
        <form method="POST">
            <input type="hidden" name="route" value="pass_recovery">
            <div class="form-group">
                <input type="text" class="form-control-lg mt-2 w-100" placeholder="Correo electrónico" name="email" value="<?= $email ?>" required>
            </div>
            <br>
            <?php if ($button == "send"): ?>
                <?php if ($isValid): ?>
                    <div class="alert alert-success" role="alert">
                        <?= $msg ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $msg ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($button != "send" || !$isValid): ?>
                <button class="btn btn-lg btn-info btn-block font-weight-bold mt-4" type="submit" name="button" value="send">Enviar</button>
            <?php endif; ?>
        </form>
        <form>
            <button class="btn btn-lg btn-secondary btn-block font-weight-bold mt-4 mb-3" type="submit">Ir a página de inicio</button>
        </form>
    </div>
</div>

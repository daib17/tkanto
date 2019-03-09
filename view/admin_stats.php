<div class="container main-container">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" href="?route=admin_calendar_1"><i class="far fa-calendar-alt"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?route=admin_students_1"><i class="fas fa-users"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?route=admin_recent"><i class="fas fa-database"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="?route=admin_stats"><span class="fa-text">Datos</span></a>
        </li>
    </ul>
    <div class="container main-container-inner">
        <form method="POST">
            <input type="hidden" name="route" value="admin_stats">
            <div class="input-group">
                <select class="form-control" name="fromDay">
                    <?= $spinnerDayFrom ?>
                </select>
                <select class="form-control" name="fromMonth">
                    <?= $spinnerMonthFrom ?>
                </select>
                <select class="form-control" name="fromYear">
                    <?= $spinnerYearFrom ?>
                </select>
            </div>
            <div class="input-group mt-2">
                <select class="form-control" name="toDay">
                    <?= $spinnerDayTo ?>
                </select>
                <select class="form-control" name="toMonth">
                    <?= $spinnerMonthTo ?>
                </select>
                <select class="form-control" name="toYear">
                    <?= $spinnerYearTo ?>
                </select>
            </div>
            <div class="input-group">
                <div class="input-group-prepend mt-2">
                    <span class="input-group-text"><i class="far fa-user"></i></span>
                </div>
                <select class="form-control mt-2" name="student">
                    <?= $spinnerStudents ?>
                </select>
            </div>
            <div class="float-left input-group mt-2 mb-4 w-50">
                <div class="input-group-prepend">
                    <span class="input-group-text">Tipo</span>
                </div>
                <select class="form-control" name="type">
                    <option value="acc">Total</option>
                    <option value="list" <?= $selList ?>>Lista</option>
                </select>
            </div>
            <div class="float-right input-group mt-2 pl-5 w-50">
                <div class="input-group-prepend">
                    <span class="input-group-text">#</span>
                </div>
                <select class="form-control" name="limit">
                    <option value="10">10</option>
                    <option value="20" <?= $selLimit20 ?>>20</option>
                    <option value="30" <?= $selLimit30 ?>>30</option>
                </select>
            </div>
            <div class="clearfix">
                <button class="btn btn-md btn-info btn-block w-100 font-weight-bold my-4" type="submit" name="button" value="run">Buscar</button>
            </div>
        </form>
        <?= $table ?>
    </div>
</div>

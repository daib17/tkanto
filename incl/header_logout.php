<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="img/favicon.png"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet"> -->
    <link rel="stylesheet" href="css/style.css">
    <title><?= $title ?></title>
</head>
<body>
    <nav class="navbar bg-dark navbar-dark fixed-top">
        <div class="container">
            <span class="navbar-brand">TecniKanto (<?= $_SESSION['user'] ?>)</span>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="?route=login" class="nav-link">Salir</a>
                </li>
            </ul>
        </div>
    </nav>

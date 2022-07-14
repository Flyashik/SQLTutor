<?php if ($_GET['hash']) : ?>
    <?php $hash = $_GET['hash']; ?>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Курс по SQL</title>
        <link rel="stylesheet" type="text/css" href="../css/reset.css">
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <link rel="stylesheet" type="text/css" href="../css/adaptive.css">
        <!-- Favicon -->
        <link rel="shortcut icon" href="../img/fav.svg" type="image/svg+xml">
        <!-- Favicon -->
    </head>

    <div class="wrap bg-light" id="center-form-section">
        <form action="" method="post"
            class="form-sign-in col-12 col-sm-6 col-md-5 col-lg-4 col-xl-2 m-auto text-center">
            <a href="index.html">
                <img class="mb-5" src="../img/logo2.svg" width="80" alt="">
            </a>
            <h3 class="h3 font-weight-bold mb-4">Восстановление пароля</h3>
            <div class="form-group mb-2" style="margin-top: 30px">
                <b style="color: #494949;">Новый пароль</b>
                <input type="password" class="form-control" id="password" name="password" placeholder="Пароль" minlength="8" maxlength="25" required>
            </div>
            <button class="btn btn-lg send-btn btn-block" id="accept-button" type="submit" disabled>Продолжить</button>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/input_text_checker.js"></script>

    <?php 
        if($_POST['password'])
        {
            require 'connect_db.php';
            $password = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST['password']))));
            $hash_pass = password_hash($password, PASSWORD_DEFAULT);

            $prepare = mysqli_prepare($conn, 'UPDATE `users` SET `password` = ? WHERE `hash` = ?');
            mysqli_stmt_bind_param($prepare, 'ss', $hash_pass, $hash);
            mysqli_stmt_execute($prepare);
            mysqli_stmt_close($prepare);

            mysqli_close($conn);
            $_SESSION['pass_upd'] = "Пароль успешно изменен";

            header('Location: ../sign-in.html');
        }
    ?>
<?php else : ?>
    <?php echo 'Возникла непредвиденная ошибка'; ?>
<?php endif; ?>
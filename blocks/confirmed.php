<?php
require_once 'connect_db.php';

if ($_GET['hash']) {
    $hash = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_GET['hash']))));
    if ($result = mysqli_query($conn, "SELECT `id`, `email_confirmed` FROM `users` WHERE `hash`='" . $hash . "'")) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo $row['id'] . " " . $row['email_confirmed']; 
            if ($row['email_confirmed'] == 1) {
                mysqli_query($conn, "UPDATE `users` SET `email_confirmed`=0 WHERE `id`=" . $row['id']);
                $_SESSION['confirmed'] = "E-mail подтвержден";
                header('Location: ../sign-in.html');
            } else {
                echo "Что то пошло не так";
            }
        }
    } else {
        echo "Что то пошло не так";
    }
} else {
    echo "Что то пошло не так";
}

<?php
    session_start();
    
    require 'connect_db.php';

    if($_POST)
    {
        foreach($_SESSION['user'] as $name => $value)
        {
            $user[$name] = $value; 
        }
        $id = $user['id'];
        $first_name = filter_var(trim($_POST['first-name']), FILTER_SANITIZE_STRING);
        $second_name = filter_var(trim($_POST['second-name']), FILTER_SANITIZE_STRING);
        $birthdate = $_POST['birthdate'];
        $country = filter_var(trim($_POST['country']), FILTER_SANITIZE_STRING);
        $city = filter_var(trim($_POST['city']), FILTER_SANITIZE_STRING);
        $website = filter_var(trim($_POST['website']), FILTER_SANITIZE_STRING);
        $github = filter_var(trim($_POST['github']), FILTER_SANITIZE_STRING);
        $twitter = filter_var(trim($_POST['twitter']), FILTER_SANITIZE_STRING);
        $instagram = filter_var(trim($_POST['instagram']), FILTER_SANITIZE_STRING);

        $prepare = mysqli_prepare($conn, "UPDATE `users` SET `first-name` = ?, `second-name` = ?, `birthdate` = ?, `country` = ?, `city` = ?, `website` = ?, `github` = ?, `twitter` = ?, `instagram` = ? WHERE `id` = ?");
        mysqli_stmt_bind_param($prepare, "sssssssssi", $first_name, $second_name, $birthdate, $country, $city, $website, $github, $twitter, $instagram, $id);
        mysqli_stmt_execute($prepare);
        $last_id = mysqli_insert_id($conn);
        mysqli_stmt_close($prepare);


        mysqli_close($conn);

        $_SESSION['user']['first-name'] = $first_name;
        $_SESSION['user']['second-name'] = $second_name;
        $_SESSION['user']['birthdate'] = $birthdate;
        $_SESSION['user']['country'] = $country;
        $_SESSION['user']['city'] = $city;
        $_SESSION['user']['website'] = $website;
        $_SESSION['user']['github'] = $github;
        $_SESSION['user']['twitter'] = $twitter;
        $_SESSION['user']['instagram'] = $instagram;

        header('Location: /account.html');
    }
?>
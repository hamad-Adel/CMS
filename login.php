<?php
if (!session_start())
    session_start();

require_once 'includes/db.php';
require_once 'admin/includes/helpers/db_functions.php';

if(isset($_POST['login'])) {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    $username = mysqli_real_escape_string($dbh, $username);
    $password = mysqli_real_escape_string($dbh, $password);
    $sql = "SELECT * FROM `users` WHERE `username` = '{$username}' AND `password` = '{$password}' AND `role` > 0";
    $query = mysqli_query($dbh, $sql);

    if($query && $query->num_rows) {
        $result = mysqli_fetch_assoc($query);
        $_SESSION['admin_login'] = true;
        $_SESSION['id'] = $result['id'];
        $_SESSION['username'] = $result['username'];

        header("Location:admin");
    } else {
        $_SESSION['error'] = 'Wrong credentials';
        header("Location:index.php");
    }

    mysqli_free_result($query);
}
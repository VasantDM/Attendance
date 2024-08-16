<?php
require 'connection.php';
session_start();
if (isset($_REQUEST)) {

    $email = $_POST['email'];
    $password = $_POST['password'];


    $sql = "SELECT * FROM register WHERE register.email = '$email' AND register.password = '$password'";
    // print_r($sql);exit;
    $res = mysqli_query($conn, $sql);
    $result = $res->fetch_assoc();
    if (empty($result)) {
        echo "0";
    } else {
        $_SESSION["email"] = $email;
        echo "1";
    }
}



<?php
    session_start();
    include ("./database_access_functions.php");
    // event logging
    $event_type = "log out";
    $event_value = strval($_SERVER['REMOTE_ADDR']); // strval($_SERVER['REMOTE_ADDR'])
    include("./event_log_module.php");
    // set login status to false and clear the logged in user info
    $_SESSION["logged_in"] = false;
    $_SESSION["logged_in_user"] = "";
    $_SESSION["is_admin"] = "";
    header("Location: ./logout_confirmation.php");
?>
<?php
    session_start();
    // set login status to false and clear the logged in user info
    $_SESSION["logged_in"] = false;
    $_SESSION["logged_in_user"] = "";
    $_SESSION["is_admin"] = "";
    header("Location: ./logout_confirmation.php");
?>
<?php
include("./common_utility_functions.php");
include("./database_access_functions.php");
session_start();
$username_input = $_POST["username"];
$password_input = $_POST["password"];

$username_is_valid = false;
$password_is_valid = false;
$login_is_valid = false;
$logged_in_user = ""; // the user id of the user

// if the username and password inputs are both not blank, check if they exist in the database
if ((!empty($username_input)) && (!empty($password_input))) {
    $test_records = $database_access_object->prepared_statment_select_on_two_records("users","user_name",$username_input,"s","password",hash('sha256', $password_input),"s");
    // there should be exactly 1 row of records
    if (count($test_records) == 1) {
        $username_is_valid = true;
        $password_is_valid = true;
        $_SESSION["logged_in_user"] = $test_records[0]["user_id"];
        $_SESSION["is_admin"] = $test_records[0]["is_admin_y_n"];
    }
}

// if the username and password are valid, set login to true and redirect to the manage user page
if (($username_is_valid == true) && ($password_is_valid == true)) {
    $login_is_valid = true;
    $event_type = "login success";
    $event_value = strval($_SERVER['REMOTE_ADDR']); // strval($_SERVER['REMOTE_ADDR'])
    include("./event_log_module.php");
}
if ($login_is_valid == true) {
    // save the user id of the user into a session variable
    $_SESSION["logged_in"] = true;
    $login_check = "f";
    if ($_SESSION["logged_in"] == true) {
        $login_check = "p";
    }
    header("Location: ./manage_account.php?lgp=$login_check");
}
?>
<!-- if the username and password are not valid, redirect back to the login page with the error message. There are better ways to do this, but this lazy programmer can't be bothered to change this -->
 <form id="if_auto_submit" action="./login.php" method="post">
    <?php
    if ($login_is_valid == false) {
        $event_type = "failed login attempt";
        $event_value = strval($_SERVER['REMOTE_ADDR']); // strval($_SERVER['REMOTE_ADDR'])
        include("./event_log_module.php");
        echo '<input type="text" name="login_error" id="login_error" value="Login failed. Username or password is incorrect.">';
        echo '<script>document.getElementById("if_auto_submit").submit();</script>';
    }
    ?>
</form>

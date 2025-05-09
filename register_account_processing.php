<?php
# check if the username and password inputs are blank
// ### link the database access functions
include "./database_access_functions.php";
include "./common_utility_functions.php";
$username_input = trim_spaces_from_string($_POST["username"]);
$password_input = trim_spaces_from_string($_POST["password"]);
// ### to do later -> validate that the username and password are valid and do not already exist, and the the user name does not contain "admin"
$username_is_valid = false; // set to true if all username checks pass
$username_is_unique = false;
$username_does_not_contain_admin = false;
# check that the username does not already exist - the username should not already exist in the database
if ($database_access_object->check_if_value_exists($username_input, "s", "user_name", "users") == false) {
    $username_is_unique = true;
}
# check if the username contains "admin" - this should not be the case
if (check_if_string_contains_substring($username_input,"admin") == false) {
    $username_does_not_contain_admin = true;
}
# if all username checks pass, set $username_is_valid to true
if (($username_is_unique == true) && ($username_does_not_contain_admin == true))  {
    $username_is_valid = true;
}
// ### temporary measure until backend code is completed -> redirect to registration success page
header("Location: ./register_account_success.php");

?>
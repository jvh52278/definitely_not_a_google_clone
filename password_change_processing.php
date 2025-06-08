<?php
session_start();
// if a user is not logged in, redirect back to login page
if ($_SESSION["logged_in"] != true) {
    header("Location: ./login.php");
}
include "./database_access_functions.php";
include "./common_utility_functions.php";

// retrieve post inputs
$current_password_input = trim_spaces_from_string(check_and_replace_if_variable_is_empty($_POST["old_password"]));
$new_password_input = trim_spaces_from_string(check_and_replace_if_variable_is_empty($_POST["new_password"]));
$retyped_password_input = trim_spaces_from_string(check_and_replace_if_variable_is_empty($_POST["confirm_new_password"]));

$password_change_is_valid = false;
// are these needed?
$current_password_is_correct = false;
$new_password_is_not_blank = false;
$new_password_is_not_the_same_as_current = false;
$retyped_password_is_not_blank = false;
$retyped_password_matches_new_password = false;
//

$current_password_error = "";
$new_password_error = "";
$retype_password_error = "";
// check if current password is blank
if (empty($current_password_input)) {
    $current_password_error = "This field cannot be blank";
} else {
    // only if the current password is not blank - check if current password is correct
    $test_password_retrieval = $database_access_object->prepared_statment_select_on_one_record("users","user_id",$_SESSION["logged_in_user"], "s");
    if ($test_password_retrieval[0]["password"] != hash('sha256', $current_password_input)) {
        $current_password_error = "Incorrect password. This should be your current password";
    } else {
        // only if current password is correct - check if new password is blank
        if (empty($new_password_input)) {
            $new_password_error = "This field cannot be blank";
        } else {
            // only if the new password is not blank - check if new password is the same as current password
            if ($test_password_retrieval[0]["password"] == hash('sha256', $new_password_input)) {
                $new_password_error = "The new password cannot be the same as your current password";
            } else {
                // only if the new password is not the same as the current one - check if retyped password is blank
                if (empty($retyped_password_input)) {
                    $retype_password_error = "This field cannot be blank";
                } else {
                    // only if the retyped password is not blank - check if retyped password matches the new password
                    if ($new_password_input != $retyped_password_input) {
                        $retype_password_error = "Passwords do not match";
                        $new_password_error = "Passwords do not match";
                    } else {
                        // if all tests pass, the password change can proceed
                        $password_change_is_valid = true;
                    }
                }
            }
        }
    }
}

// if the password change is not valid, redirect back with error messages
if ($password_change_is_valid == false) {
    echo '<form id="if_auto_submit_final" action="./change_password.php" method="post">';
    // input 1
    $input_type_1 = "hidden";
    $name_of_input_1 = "current_password_error_message";
    $input_value_1 = $current_password_error;
    $input_string_1 = '<input type="'.$input_type_1.'" name="'.$name_of_input_1.'" id="'.$name_of_input_1.'" value="'.$input_value_1.'">';
    echo $input_string_1;
    // input 2
    $input_type_2 = "hidden";
    $name_of_input_2 = "new_password_error_message";
    $input_value_2 = $new_password_error;
    $input_string_2 = '<input type="'.$input_type_2.'" name="'.$name_of_input_2.'" id="'.$name_of_input_2.'" value="'.$input_value_2.'">';
    echo $input_string_2;
    // input 3
    $input_type_3 = "hidden";
    $name_of_input_3 = "retype_password_error_message";
    $input_value_3 = $retype_password_error;
    $input_string_3 = '<input type="'.$input_type_3.'" name="'.$name_of_input_3.'" id="'.$name_of_input_3.'" value="'.$input_value_3.'">';
    echo $input_string_3;
    echo '<script>document.getElementById("if_auto_submit_final").submit();</script>';
    echo "</form>";
}
// if the password change is valid, redirect back with success message
if ($password_change_is_valid == true) {
    // change the password
    $database_access_object->prepared_statment_update_on_one_record("users", "user_id", $_SESSION["logged_in_user"], "s", "password", hash('sha256', $new_password_input), "s");
    // redirect back with success message
    echo '<form id="if_auto_submit_final" action="./change_password.php" method="post">';
    $input_type_1 = "hidden";
    $name_of_input_1 = "success_message";
    $input_value_1 = "Password change successful";
    $input_string_1 = '<input type="'.$input_type_1.'" name="'.$name_of_input_1.'" id="'.$name_of_input_1.'" value="'.$input_value_1.'">';
    echo $input_string_1;
    echo '<script>document.getElementById("if_auto_submit_final").submit();</script>';
    echo "</form>";
}
?>
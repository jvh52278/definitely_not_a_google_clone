<?php
session_start();
# check if the username and password inputs are blank
// ### link the database access functions
include "./database_access_functions.php";
include "./common_utility_functions.php";
$username_error = "";
$password_error = "";
$username_input = trim_spaces_from_string($_POST["username"]);
$password_input = trim_spaces_from_string($_POST["password"]);
// validate that the username and password are valid and do not already exist, and the the user name does not contain "admin"
$username_is_valid = false; // set to true if all username checks pass
$password_is_valid = false; // set to true if all password checks pass
$username_is_unique = false;
$username_does_not_contain_admin = false;
$username_is_not_empty = false;
$password_is_not_blank = false;
// # check that the username does not already exist - the username should not already exist in the database
if (!empty($username_input)) {
    if ($database_access_object->check_if_value_exists($username_input, "s", "user_name", "users") == false) {
        $username_is_unique = true;
    } else {
        $username_error = $username_error."Username already exists<br>";
    }
}
// # check if the username contains "admin" - this should not be the case
if (check_if_string_contains_substring($username_input,"admin") == false) {
    $username_does_not_contain_admin = true;
} else {
    $username_error = $username_error."Username cannot contain 'admin'<br>";
}
// # check if the username is not blank - this should not be the case
if (!empty($username_input)) {
    $username_is_not_empty =  true;
} else {
    $username_error = $username_error."This field cannot be blank<br>";
}
// # if all username checks pass, set $username_is_valid to true
if (($username_is_unique == true) && ($username_does_not_contain_admin == true) && ($username_is_not_empty == true))  {
    $username_is_valid = true;
}
// # check if the password is blank - this should not be the case
if (!empty($password_input)) {
    $password_is_not_blank = true;
} else {
    $password_error = $password_error."Password cannot be blank<br>";
}
// # if all password checks pass, set $password_is_valid to true
if ($password_is_not_blank == true) {
    $password_is_valid = true;
}

// # if username and password checks pass, create the new account and redirect to registration success page
if (($username_is_valid == true) && ($password_is_valid == true)) {
    // create new account
    // database access variables
    $database_user = $ref_database_username; // the database user -- user name
    $database_user_password = $ref_database_user_password; // the password of the database user
    $database_name = $ref_database_name; // the name of the database that is being accessed
    $sql_server_name = $ref_server_name; // is usually localhost
    // query customization variables -- to define fixed parts of the query
    $table_to_access = "users";
    $user_id_to_insert = $database_access_object->create_random_string();
    // check if the user id already exists
    $user_id_guaranteed_to_be_unique = false;
    while ($user_id_guaranteed_to_be_unique == false) {
        $duplicate_exists = $database_access_object->check_if_value_exists($user_id_to_insert, "s", "user_id", "users");
        if ($duplicate_exists == false) {
            $user_id_guaranteed_to_be_unique = true;
        }
        // if the user id already exists, generate a new one to be tested in the next loop iteration
        if ($duplicate_exists == true) {
            $user_id_to_insert = $database_access_object->create_random_string();
        }
    }
    //
    $username_to_insert = $username_input;
    $password_to_insert = hash('sha256', $password_input); // hash the password before inserting it into the database - you don't want to expose user passwords if there is a data breach
    $admin_value = "no";
    // query customization variables -- for the dynamic / user input 
        // this is the ?
        // identifying_record_datatype: i -> int
        // identifying_record_datatype: d -> float
        // identifying_record_datatype: s -> string
        // identifying_record_datatype: b -> blob, sent in packets
    // ### run the sql query ###
    $database_connection = new mysqli($sql_server_name, $database_user, $database_user_password, $database_name);
    // step 1A: prepare the query
        // create the query, but put a ? where a user / dynamic input would be
    $sql_query = "INSERT INTO $table_to_access (user_id, user_name, password, is_admin_y_n) VALUES (?, ?, ?, ?)";
    $prepared_sql_query = $database_connection->prepare($sql_query);
    // step 1B: bind the user / dynamic input variables to fixed data types
        // bind_param('|input_1_datatype|input_2_datatype|....', $input_1, $input_2, .....)
        // NOTE: the inputs go in order that they would appear in the query, left to right, where the ? are
        // datatype: i -> int
        // datatype: d -> float
        // datatype: s -> string
        // datatype: b -> blob, sent in packets
    $prepared_sql_query->bind_param("ssss", $user_id_to_insert, $username_to_insert, $password_to_insert, $admin_value);
    // step 2A: execute the prepared query
    $prepared_sql_query->execute(); // NOTE: this does not return the result, if any exists
    // close the prepared query
    $prepared_sql_query->close();
    // redirect to success page
    header("Location: ./register_account_success.php");
}
?>
<!-- if username and/or password check fails, redirect back with error messages -->
<form id="error_auto_submit" action="./register_account.php" method="post">
    <?php
    if (($password_is_valid == false) || ($username_is_valid == false)) {
        echo '<input type="text" name="username_error" id="username_error" value="'.$username_error.'">';
        echo '<input type="text" name="password_error" id="password_error" value="'.$password_error.'">';
        echo '<script>document.getElementById("error_auto_submit").submit();</script>';
    }
    ?>
</form>
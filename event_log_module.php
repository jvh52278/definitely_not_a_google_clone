<?php
/*
activity_log
>>
code_id varchar(500) NOT NULL,
activity_type varchar(500) NOT NULL,
associated_user_id varchar(500) NOT NULL,
date_generated int NOT NULL,
code_value varchar(5000) NOT NULL,
*/
/*
put this on every page where this module is used:
$event_type = "";
$event_value = "";
*/
    # temporary database access
    # include ("./database_access_functions.php");

    # create event id
    $i_code_id = ""; // code_id
    $unique_id_generated = false;
    while ($unique_id_generated == false) {
        $test_value = $database_access_object->create_random_string();
        if ($database_access_object->check_if_value_exists($test_value, "s", "code_id", "activity_log") != true) {
            $i_code_id = $test_value;
            $unique_id_generated = true;
        }
    }
    # set activity type
    $i_activity_type = $event_type;
    # set user id for event logging
    $i_associated_user_id = "";
    if ($_SESSION["logged_in_user"] == "" || $_SESSION["logged_in"] == false) {
        $i_associated_user_id = "an1";
    } else {
        $i_associated_user_id = $_SESSION["logged_in_user"];
    }
    # create event date
    $i_date_generated = round(microtime(true),3)*1000;
    # set event value
    $i_code_value = $event_value;
    # insert database record
?>
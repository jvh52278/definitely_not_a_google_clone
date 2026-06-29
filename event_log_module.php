<?php
/*
activity_log
>>
code_id varchar(500) NOT NULL,
activity_type varchar(500) NOT NULL,
associated_user_id varchar(500) NOT NULL,
date_generated int NOT NULL,
code_value varchar(5000) NOT NULL,

INSERT INTO activity_log (code_id, activity_type, associated_user_id, date_generated, code_value) VALUES (?, ?, ?, ?, ?);

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
    $i_activity_type = "";
    if (empty($event_type)) {
        $i_activity_type = "null direct access";
    } else {
    $i_activity_type = $event_type;
    }
    # set user id for event logging
    $i_associated_user_id = "";
    if ($_SESSION["logged_in_user"] == "" || $_SESSION["logged_in"] == false) {
        $i_associated_user_id = "an1";
    } else {
        $i_associated_user_id = $_SESSION["logged_in_user"];
    }
    # create event date
    $i_date_generated = time();
    # set event value
    $i_code_value = "";
    if (empty($event_value)) {
        $i_code_value = "null direct access";
    } else {
        $i_code_value = $event_value;
    }
    # insert database record
    // ### prepare access and customization variables ###
    // database access variables
    $database_user = $ref_database_username; // the database user -- user name
    $database_user_password = $ref_database_user_password; // the password of the database user
    $database_name = $ref_database_name; // the name of the database that is being accessed
    $sql_server_name = $ref_server_name; // is usually localhost
    // query customization variables -- for the dynamic / user input 
        // this is the ?
    $identifying_record_data_type = "sssis";
        // identifying_record_datatype: i -> int
        // identifying_record_datatype: d -> float
        // identifying_record_datatype: s -> string
        // identifying_record_datatype: b -> blob, sent in packets
    // ### run the sql query ###
    $database_connection = new mysqli($sql_server_name, $database_user, $database_user_password, $database_name);
    // step 1A: prepare the query
        // create the query, but put a ? where a user / dynamic input would be
    $sql_query = "INSERT INTO activity_log (code_id, activity_type, associated_user_id, date_generated, code_value) VALUES (?, ?, ?, ?, ?);";
    $prepared_sql_query = $database_connection->prepare($sql_query);
    // step 1B: bind the user / dynamic input variables to fixed data types
        // bind_param('|input_1_datatype|input_2_datatype|....', $input_1, $input_2, .....)
        // NOTE: the inputs go in order that they would appear in the query, left to right, where the ? are
        // datatype: i -> int
        // datatype: d -> float
        // datatype: s -> string
        // datatype: b -> blob, sent in packets
    $prepared_sql_query->bind_param($identifying_record_data_type, $i_code_id, $i_activity_type, $i_associated_user_id, $i_date_generated, $i_code_value);
    // step 2A: execute the prepared query
    $prepared_sql_query->execute(); // NOTE: this does not return the result, if any exists
    // step 2B: retrieve the results of the query and put them into an array
    // close the prepared query
    $prepared_sql_query->close();
?>
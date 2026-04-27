<?php
    session_start();
    //
    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    //
    include "./database_access_functions.php";
    include "./common_utility_functions.php";

    $valid_comment = false;

    $comment_value = trim($_POST["comment_text"]);
    
    // insert the comment into the database
    // prepare the comment id
    $comment_id = "";
    $unique_id_found = false;
    while ($unique_id_found == false) {
        $test_id = $database_access_object->create_random_string();
        if ($database_access_object->check_if_value_exists($test_id, "s", "comment_id", "comments")) {
            $unique_id_found = false;
        } else {
            $unique_id_found = true;
            $comment_id = $test_id;
        }
    }
    // prepare the video id
    $associated_video_id = $_SESSION["current_video"];
    // prepare the poster user id and poster username
    $posted_by_user_username = "";
    $posted_by_user = "";
    if ($_SESSION["logged_in_user"] == "" || $_SESSION["logged_in"] == false) {
        $posted_by_user = "an1";
        $posted_by_user_username = "[Object object]";
    } else {
        $posted_by_user = $_SESSION["logged_in_user"];
        $user_info_retrieval = $database_access_object->prepared_statment_select_on_one_record("users", "user_id", $posted_by_user, "s");
    }
    // prepare the comment text for database insertion
    $comment_text = "";
    // if the comment text is not empty, save to database
    if (!empty(trim($comment_value))) {
        $valid_comment = true;
        if (strlen($comment_value) <= 500) {
            $comment_text = $comment_value;
        } else {
            $temp_string = "";
            for ($x = 0; $x <= 500; $x = $x + 1) {
                $current_char = $comment_value[$x];
                $temp_string = $temp_string.$current_char;
            }
            $comment_text = $temp_string;
        }
    }
    // prepare the posted date
    $date_posted = time();
    if ($valid_comment == true) {
        // save comment to database
        // database access variables
        $database_user = $ref_database_username; // the database user -- user name
        $database_user_password = $ref_database_user_password; // the password of the database user
        $database_name = $ref_database_name; // the name of the database that is being accessed
        $sql_server_name = $ref_server_name; // is usually localhost
        // ### run the sql query ###
        $database_connection = new mysqli($sql_server_name, $database_user, $database_user_password, $database_name);
        // step 1A: prepare the query
            // create the query, but put a ? where a user / dynamic input would be
        $sql_query = "INSERT INTO comments (comment_id, associated_video_id, commenter_user_id, commenter_username, comment_text, posted_date) VALUES (?, ?, ?, ?, ?, ?)";
        $prepared_sql_query = $database_connection->prepare($sql_query);
        // step 1B: bind the user / dynamic input variables to fixed data types
            // bind_param('|input_1_datatype|input_2_datatype|....', $input_1, $input_2, .....)
            // NOTE: the inputs go in order that they would appear in the query, left to right, where the ? are
            // datatype: i -> int
            // datatype: d -> float
            // datatype: s -> string
            // datatype: b -> blob, sent in packets
        $prepared_sql_query->bind_param("sssssi", $comment_id, $associated_video_id, $posted_by_user, $posted_by_user_username, $comment_text,$date_posted);
        // step 2A: execute the prepared query
        $prepared_sql_query->execute(); // NOTE: this does not return the result, if any exists
        // close the prepared query
        $prepared_sql_query->close();
    }

    header("Location: ./comment_frame_section.php");
    //header("refresh:2;url=./comment_frame_section.php");
?>
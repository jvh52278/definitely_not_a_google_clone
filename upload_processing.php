<?php
session_start();
// if a user is not logged in, redirect back to login page
if ($_SESSION["logged_in"] != true) {
    header("Location: ./login.php");
}
include("./database_access_functions.php");
include("./common_utility_functions.php");
include("./global_control_variables.php");
$user_info_retrieval = $database_access_object->prepared_statment_select_on_one_record("users", "user_id", $_SESSION["logged_in_user"], "s");
// retrieve form inputs
$input_video_title = trim_spaces_from_string($_POST["video_title"]);
$input_video_description = trim_spaces_from_string($_POST["video_description"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Processing</title>
    <link rel="stylesheet" href="./css/colours.css">
</head>
<body>
    <h1 id="title_header">Your upload is being processed</h1>
    <img id="body_image" src="./images/please_accept_this_drawing_of_a_spider_as_payment.png" alt="a very old and very specific reference">
    <?php
        $form_inputs_are_valid = false;
        $preprocessing_checks_are_valid = false; // virus scanning, number of pre-existing uploads
        $primary_checks_are_valid = false ;// file size, video length, video format
        
        $top_message_status = 0;
        $upload_error_status = 0;
        $title_error_status = 0;
        $description_error_status = 0;
        
        // preprocessing checks
        if ($force_preprocessing_virus_scan == false && $force_upload_limiter == false) {
            $preprocessing_checks_are_valid = true;
        } else {
            // -- virus scan
            // -- user upload count
        }
        // check if form inputs are valid
        if ($preprocessing_checks_are_valid == true) {
            // -- check if the upload field is blank -> that a file was uploaded
            $upload_field_is_not_blank = false;
            // -- check if the title field is blank
            $title_is_not_blank = false;
            if (!empty($input_video_title)) {
                $title_is_not_blank = true;
            } else {
                $title_error_status = 1;
            }
            // -- check if the description field is blank
            $description_is_not_blank = false;
            if (!empty($input_video_description)) {
                $description_is_not_blank = true;
            } else { 
                $description_error_status = 1;
            }
            if ($upload_field_is_not_blank == true && $title_is_not_blank == true && $description_is_not_blank == true) {
                $form_inputs_are_valid = true;
            }
        }
        // primary checks, if applicable
        if ($form_inputs_are_valid == true) {
            // check file size
            $correct_file_size = false;
            // check video length
            $correct_video_length = false;
            // check video format
            $correct_video_format = false;
            if ($correct_file_size == true && $correct_video_length == true && $correct_video_format == true) {
                $primary_checks_are_valid = true;
            }
        }

        if ($form_inputs_are_valid == true && $preprocessing_checks_are_valid == true && $primary_checks_are_valid == true) {
            header("Location: ./upload_success.php");
        } else {
            // redirect back with errors
            header("Location: ./upload_video.php?top_message_code=$top_message_status&upload_message_code=$upload_error_status&title_message_code=$title_error_status&description_message_code=$description_error_status");
        }
    ?>
</body>
</html>
<style>
    body {
        background-color: var(--primary_page_background);
    }
    #title_header {
        background-color: var(--primary_element_background);
        color: var(--primary_page_background);
        width: 100%;
        text-align: center;
        padding: 20px;
    }
    #body_image {
    width: 60%;
    margin-left: auto;
    margin-right: auto;
    margin-top: 10px;
    margin-bottom: 10px;
    display: block;
    }
</style>
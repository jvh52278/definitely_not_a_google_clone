<?php
    //
    /*
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    */
    //
    session_start();
    // if a user is not logged in, redirect back to login page
    if ($_SESSION["logged_in"] != true) {
        header("Location: ./login.php");
    }
    //main.php
    include "./database_access_functions.php";
    include "./common_utility_functions.php";
    include "./global_control_variables.php";
    $user_info_retrieval = $database_access_object->prepared_statment_select_on_one_record("users", "user_id", $_SESSION["logged_in_user"], "s");

    $video_to_delete = $_GET["v"];
    $send_back_code = $_GET["rd"];
    $back_redirect_link = "";
    if ($send_back_code == "m") {
        $back_redirect_link = "manage_uploads.php";
    }
    if ($send_back_code == "a") {
        $back_redirect_link = "upload_review.php";
    }
    if ($send_back_code != "a" || $send_back_code != "m") {
        header("Location: ./main.php");
    }

    $last_page = $_GET["last_page_displayed"];
    if (empty($last_page)) {
        $last_page = 0;
    }
    
    // delete a video if
    // - user is logged in
    // - then, only if the video exists in the database
    // - then, only if the video uploader matches the logged in user OR the user is an admin
    // if the user is allowed to delete the video
    // - try catch delete the initial uploaded video file
    // - then, if that fails, try catch delete the downscaled video file
    // - delete the database record, even if both file deletes failed
    // - if there are 2 fail file deletes, redirect back with message -> "Something happened but it's nothing to worry about"
    // - if there are less than 2 file fail file deletes, redirect to normal redirect page -> "video delete successful"
    if ($_SESSION["logged_in"] == true) {
        // verify that the video exists in the database
        $test_record = $database_access_object->prepared_statment_select_on_one_record("videos", "video_id", $video_to_delete, "s");
        if (count($test_record) == 1) {
            // if the video record exists, verify that the video uploader matches the logged in user or that the user is admin
            $user_check_value = $_SESSION["logged_in_user"];
            if ($test_record[0]["uploader"] == $user_check_value || $user_info_retrieval[0]["is_admin_y_n"] == "y") {
                // if the user is allowed to delete the video, attempt to delete both recorded video file paths
                $failed_file_deletes = 0;
                $failed_record_deletes = 0;
                $path_to_original_upload_file = $_SERVER['DOCUMENT_ROOT'].$test_record[0]["path_to_video_file"];
                $path_to_alt_file = $_SERVER['DOCUMENT_ROOT'].$test_record[0]["path_to_video_file_alt"];
                $path_to_thumbnail = $_SERVER['DOCUMENT_ROOT'].$test_record[0]["path_to_thumbnail"];
                // attempt to delete the thumbnail image
                try {
                    unlink($path_to_thumbnail);
                }
                catch (Exception $e) {
                    $failed_file_deletes = $failed_file_deletes + 1;
                }
                // attempt to delete the original uploaded file
                try {
                    unlink($path_to_original_upload_file);
                }
                catch (Exception $e) {
                    $failed_file_deletes = $failed_file_deletes + 1;
                }
                // attempt to delete the alt file
                try {
                    unlink($path_to_alt_file);
                }
                catch (Exception $e) {
                    $failed_file_deletes = $failed_file_deletes + 1;
                }
                // in all cases, delete the database record
                try {
                    $database_access_object->prepared_statment_delete_on_one_record("videos", "video_id", $video_to_delete, "s");
                }
                catch (Exception $e) {
                    $failed_record_deletes = $failed_record_deletes + 1;
                }
                // redirect back
                //"?last_page_displayed=".urlencode(strval($last_page)) 
                //$self_redirect_link
                $last_page_send_back = urlencode(strval($last_page));
                $message_code = "";
                if ($failed_file_deletes > 0 && $failed_record_deletes == 0) {
                    $message_code = "1";
                    // MSG-IO_ERR_1: "Something happened, but don't worry about it"
                }
                if ($failed_file_deletes > 0 && $failed_record_deletes > 0) {
                    $message_code = "2";
                    // MSG-IO_DB_ERR_1: "Something happened, but don't worry about it"
                }
                if ($failed_file_deletes == 0 && $failed_record_deletes > 0) {
                    $message_code = "3";
                    // MSG-DB_ERR_1: "Something happened, but don't worry about it"
                }
                if ($failed_file_deletes == 0 && $failed_record_deletes == 0) {
                    $message_code = "4";
                    // Video has been deleted"
                }
                $return_link = $back_redirect_link."?last_page_displayed=".$last_page_send_back."&tmc=".$message_code;
                $complete_return_code = "Location: ".$return_link;
                header($complete_return_code);
            } else {
                header("Location: ./main.php");
            }
        } else {
            $return_link = $back_redirect_link."?last_page_displayed=".$last_page_send_back."&tmc=2";
            $complete_return_code = "Location: ".$return_link;
            header($complete_return_code);
        }
    } else {
        header("Location: ./main.php");
    }
?>
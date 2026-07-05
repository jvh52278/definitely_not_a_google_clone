<?php
    session_start();
    // if a user is not logged in, redirect back to login page
    if ($_SESSION["logged_in"] != true) {
        header("Location: ./login.php");
    }
    include "./database_access_functions.php";
    include "./common_utility_functions.php";
    $user_info_retrieval = $database_access_object->prepared_statment_select_on_one_record("users", "user_id", $_SESSION["logged_in_user"], "s");
    // redirect if the user is not admin
    if ($user_info_retrieval[0]["is_admin_y_n"] != "y") {
        header("Location: ./main.php");
    }

    $all_uploads = $database_access_object->retrieve_all_records_from_table("videos");
    $total_upload_count = count($all_uploads);

    $delete_list = array();

    // create the delete list
    if (count($all_uploads) > 20) {
        for ($x = 0; $x < count($all_uploads); $x = $x + 1) {
            $current_item_id = $all_uploads[$x]["video_id"];
            $uploader_id = $all_uploads[$x]["uploader"];
            $uploader_info = $database_access_object->prepared_statment_select_on_one_record("users", "user_id", $uploader_id, "s");
            $admin_value = $uploader_info[0]["is_admin_y_n"];
            if ($admin_value != "y") {
                $max_range = 30;
                $random_int = rand(1, 100);
                if ($random_int > $max_range) {
                    array_push($delete_list, $current_item_id);
                }
            }
        }
    } else {
        for ($x = 0; $x < count($all_uploads); $x = $x + 1) {
            $current_item_id = $all_uploads[$x]["video_id"];
            $uploader_id = $all_uploads[$x]["uploader"];
            $uploader_info = $database_access_object->prepared_statment_select_on_one_record("users", "user_id", $uploader_id, "s");
            $admin_value = $uploader_info[0]["is_admin_y_n"];
            if ($admin_value != "y") {
                array_push($delete_list, $current_item_id);
            }
        }
    }
    // delete all items in the delete list
    for ($x = 0; $x < count($delete_list); $x = $x + 1) {
        $log = 0;
        $video_info = $database_access_object->prepared_statment_select_on_one_record("videos", "video_id", $delete_list[$x], "s");
        $original_upload_file = $video_info[0]["path_to_video_file"];
        $alt_processed_file = $video_info[0]["path_to_video_file_alt"];
        $thumbnail_file = $video_info[0]["path_to_thumbnail"];
        $full_path_original_upload = $_SERVER['DOCUMENT_ROOT'].$original_upload_file;
        $full_path_to_alt_file = $_SERVER['DOCUMENT_ROOT'].$alt_processed_file;
        $full_path_to_thumbnail = $_SERVER['DOCUMENT_ROOT'].$thumbnail_file;
        // delete the original upload file
        try {
            unlink($full_path_original_upload);
        }
        catch (Exception $e) {
            $log = $log + 1;
        }
        // delete the alt file
        try {
            unlink($full_path_to_alt_file);
        }
        catch (Exception $e) {
            $log = $log + 1;
        }
        // delete the thumbnail
        try {
            unlink($full_path_to_thumbnail);
        }
        catch (Exception $e) {
            $log = $log + 1;
        }
        // delete the video database record
        try {
            $database_access_object->prepared_statment_delete_on_one_record("videos", "video_id", $delete_list[$x], "s");
        }
        catch (Exception $e) {
            $log = $log + 1;
        }
        // delete any comment database records
        try {
            $database_access_object->prepared_statment_delete_on_one_record("comments", "associated_video_id", $delete_list[$x], "s");
        }
        catch (Exception $e) {
            $log = $log + 1;
        }
    }


    header("Location: ./upload_review.php");
?>
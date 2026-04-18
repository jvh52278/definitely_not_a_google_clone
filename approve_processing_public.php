<?php
    session_start();
    // if a user is not logged in, redirect back to login page
    if ($_SESSION["logged_in"] != true) {
        header("Location: ./main.php");
    }
    $back_redirect_link = "./upload_review.php";
    //main.php
    include "./database_access_functions.php";
    include "./common_utility_functions.php";
    include "./global_control_variables.php";
    $user_info_retrieval = $database_access_object->prepared_statment_select_on_one_record("users", "user_id", $_SESSION["logged_in_user"], "s");

    // if the user is not admin, redirect away
    $user_admin_status = false;
    if ($user_info_retrieval[0]["is_admin_y_n"] == "y") {
        $user_admin_status = true;
    }
    if ($user_admin_status == false) {
        header("Location: ./main.php");
    }

    $video_to_edit = $_GET["v"];

    $last_page = $_GET["last_page_displayed"];
    if (empty($last_page)) {
        $last_page = 0;
    }
    $last_page_send_back = urlencode(strval($last_page));
    // check if the video exists
    $video_exists = false;
    $video_info_retrieval = $database_access_object->prepared_statment_select_on_one_record("videos", "video_id", $video_to_edit, "s");
    if (count($video_info_retrieval) == 1) {
        $video_exists = true;
    }
    // check if the video is not currently approved
    $upload_is_not_approved = false;
    if ($video_info_retrieval[0]["upload_approved_y_n"] == "n") {
        $upload_is_not_approved = true;
    }
    if ($user_admin_status == true && $video_exists == true && $upload_is_not_approved == true) {
        // mark as public
        $new_value = "y";
        $database_access_object->prepared_statment_update_on_one_record("videos", "video_id", $video_to_edit, "s", "upload_approved_y_n", $new_value, "s");
        // redirect back to review page
        $message_code = "5";
        $return_link = $back_redirect_link."?last_page_displayed=".$last_page_send_back."&tmc=".$message_code;
        $complete_return_code = "Location: ".$return_link;
        header($complete_return_code);
    } else {
        $message_code = "2";
        $return_link = $back_redirect_link."?last_page_displayed=".$last_page_send_back."&tmc=".$message_code;
        $complete_return_code = "Location: ".$return_link;
        header($complete_return_code);
    }
    //
    /*
    $message_code = "";
    $return_link = $back_redirect_link."?last_page_displayed=".$last_page_send_back."&tmc=".$message_code;
    $complete_return_code = "Location: ".$return_link;
    header($complete_return_code);
    */
?>
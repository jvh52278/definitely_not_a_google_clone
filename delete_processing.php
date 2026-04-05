<?php
    session_start();
    // if a user is not logged in, redirect back to login page
    if ($_SESSION["logged_in"] != true) {
        header("Location: ./login.php");
    }
    include "./database_access_functions.php";
    include "./common_utility_functions.php";
    $user_info_retrieval = $database_access_object->prepared_statment_select_on_one_record("users", "user_id", $_SESSION["logged_in_user"], "s");

    $video_to_delete = $_GET["v"];
    
    // delete a video if
    // - user is logged in
    // - then, only if the video exists in the database
    // - then, only if the video uploader matches the logged in user OR the user is an admin
    // if the user is allowed to delete the video
    // - try catch delete the initial uploaded video file
    // - then, if that fails, try catch delete the downscaled video file
    // - delete the database record, even if both file deletes failed
    // - if there are 2 fail file deletes, redirect to alt redirect page -> "Something happened but it's nothing to worry about"
    // - if there are less than 2 file fail file deletes, redirect to normal redirect page -> "video delete successful"
?>
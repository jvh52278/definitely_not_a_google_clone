<?php
    session_start();
    include "./database_access_functions.php";
    include "./common_utility_functions.php";
    $user_info_retrieval = $database_access_object->prepared_statment_select_on_one_record("users", "user_id", $_SESSION["logged_in_user"], "s");
    $video_info_retrieval = $database_access_object->prepared_statment_select_on_one_record("videos", "video_id", $_SESSION["current_video"], "s");
    if (count($video_info_retrieval) == 1) {
        $value_to_increment = "upvotes"; // the table collumn name of the vote value
        $current_vote_count = $video_info_retrieval[0][$value_to_increment];
        $new_value = $current_vote_count + 1;
        // reset vote count to 0 if the vote count is already at the maximum value -> make the new value 1
        $max_value = 9223372036854775807;
        if ($new_value > $max_value) {
            $new_value = 1;
        }
        // add 1 to the vote count
        $video_to_update = $_SESSION["current_video"];
        if (!empty(trim($_SESSION["current_video"]))) {
            $database_access_object->prepared_statment_update_on_one_record("videos", "video_id", $video_to_update, "s", $value_to_increment, $new_value, "i");
        }
    }
    // redirect back to vote bar
    header("Location: ./vote_bar.php");
?>
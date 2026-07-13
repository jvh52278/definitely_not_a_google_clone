<?php
    session_start();
    // if a user is not logged in, redirect back to login page
    if ($_SESSION["logged_in"] != true) {
        header("Location: ./login.php");
    }
    include "./database_access_functions.php";
    include "./common_utility_functions.php";
    include "./storage_io_check_module.php";

    $top_message_code = $_GET["tmc"];
    $top_message = "";
    if ($top_message_code == "1") {
        $top_message = "MSG-IO_ERR_1: Something happened, but don't worry about it";
    }
    if ($top_message_code == "2") {
        $top_message = "MSG-IO_DB_ERR_1: Something happened, but don't worry about it";
    }
    if ($top_message_code == "3") {
        $top_message = "MSG-DB_ERR_1: Something happened, but don't worry about it";
    }
    if ($top_message_code == "4") {
        $top_message = "Operation completed: video has been deleted";
    }
    if ($top_message_code == "5") {
        $top_message = "Operation completed: video has been approved - public";
    }
    if ($top_message_code == "6") {
        $top_message = "Operation completed: video has been approved - private";
    }

    $user_info_retrieval = $database_access_object->prepared_statment_select_on_one_record("users", "user_id", $_SESSION["logged_in_user"], "s");
    // redirect if the user is not admin
    if ($user_info_retrieval[0]["is_admin_y_n"] != "y") {
        header("Location: ./main.php");
    }
    $display_mode_input = "all"; // "full", "all" or "short"
    $override_default_start_values = true;
    $delete_option_active = false;
    $delete_button_user_in_moderation_view = false;
    $admin_moderation_mode_active = true;
    $filter_value = "n";
    $custom_start_results = $database_access_object->prepared_statment_select_on_one_record("videos", "upload_approved_y_n", $filter_value, "s"); // use if $override_default_start_values is true
    $self_redirect_link = "upload_review.php"; // the relative file path of the page
    //
    $last_page = $_GET["last_page_displayed"];// retrieve this form input for pagination view
    if (empty($last_page)) {
        $last_page = 0;
    }
    $search_input = check_and_replace_if_variable_is_empty(trim_spaces_from_string($_GET["search_terms"])); // retrieve this input for pagination view
    $seperated_search_terms = return_seperated_alnum_chars($search_input);
    // use this only if displaying in short mode
    //$ignore_this = ""; // the id of the video to not display -> to avoid showing the currently displayed video
    // retreive server stat info
    $all_uploads = $database_access_object->retrieve_all_records_from_table("videos");
    $total_upload_count = count($all_uploads);
    $storage_space_used = (round($total_size_of_upload_directory/$enforced_max_total_storage_space_used, 6))*100;
    $all_user_info = $database_access_object->retrieve_all_records_from_table("users");
    $number_of_users = count($all_user_info);
    $hp_check = "main page accessed";
    $homepage_hits = $database_access_object->prepared_statment_select_on_one_record("activity_log", "activity_type", $hp_check, "s");
    $number_of_homepage_hits = count($homepage_hits);
    $all_homepage_hits_by_ip = array();
    for ($x = 0; $x < count($homepage_hits); $x = $x + 1) {
        $add_value = $homepage_hits[$x]["code_value"];
        array_push($all_homepage_hits_by_ip, $add_value);
    }
    $sorted_hp = array_unique($all_homepage_hits_by_ip);
    $number_of_unique_homepage_visits = count($sorted_hp);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review user uploads</title>
    <link rel="stylesheet" href="./css/colours.css">
    <link rel="stylesheet" href="./css/upload_review_css.css">
    <link rel="stylesheet" href="./css/common_element_classes.css">
    <link rel="stylesheet" href="./css/search_display_full_line_css.css">
</head>
<body>
    <h1 style="text-align: center;">Review user uploads</h1>
    <p id="status_message"><?php echo $top_message ?></p>
    <h2 class="center_element"><a href="./manage_account.php">Back</a></h2>
    <p class="center_element" style="font-size: smaller;" >
        total uploads in storage:<br><?php echo $total_upload_count ?>
        <br>
        total storage space used:
        <br>
        <?php echo $storage_space_used ?>%
        <br>
        total number of user accounts
        <br>
        <?php echo $number_of_users ?>
        <br>
        number of homepage visits
        <br>
        <?php echo $number_of_homepage_hits ?>
        <br>
        number of unique visitors
        <br>
        <?php echo $number_of_unique_homepage_visits ?>
    </p>
    <a class="link_element" href="./auto_clear_storage_space.php">clear storage space</a>
    <div id="display_section">
        <br>
        <?php include("./search_display_module.php") ?>
    </div>
</body>
</html>
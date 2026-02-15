<?php
//
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//
session_start();
include "./database_access_functions.php";
include "./common_utility_functions.php";
$user_info_retrieval = $database_access_object->prepared_statment_select_on_one_record("users", "user_id", $_SESSION["logged_in_user"], "s"); 
//
$search_input = check_and_replace_if_variable_is_empty(trim_spaces_from_string($_GET["search_terms"]));
$seperated_search_terms = return_seperated_alnum_chars($search_input);
$approved_value = "y";
//
$display_mode_input = "full";
$override_default_start_values = true;
$delete_option_active = false;
$admin_moderation_mode_active = false;
$custom_start_results = $database_access_object->prepared_statment_select_on_one_record("videos", "upload_approved_y_n", $approved_value, "s");
//
$last_page = $_GET["last_page_displayed"];
if (empty($last_page)) {
    $last_page = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search: <?php echo $search_input ?></title>
    <link rel="stylesheet" href="./css/colours.css">
    <link rel="stylesheet" href="./css/common_element_classes.css">
    <link rel="stylesheet" href="./css/search_css.css">
</head>
<body>
    <div id="page_content">
        <?php include("./common_header.php") ?>
        <?php include("./search_display_module.php") ?>
    </div>
</body>
</html>

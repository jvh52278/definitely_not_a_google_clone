<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home page</title>
    <link rel="stylesheet" href="./css/colours.css">
    <link rel="stylesheet" href="./css/main_css.css">
    <link rel="stylesheet" href="./css/search_display_full_line_css.css">
</head>
<body>
    <!-- div containing page content -->
    <div id="main_content">
        <!-- common header section-->
        <?php 
            session_start();
            // link the common header file
            include "./common_header.php";
            include "./common_utility_functions.php";
            include "./database_access_functions.php";
            // link the search display module
            //
            $approved_value = "y";
            $display_mode_input = "all"; // "full", "all" or "short"
            $override_default_start_values = true;
            $delete_option_active = false;
            $admin_moderation_mode_active = false;
            $custom_start_results = $database_access_object->prepared_statment_select_on_one_record("videos", "upload_approved_y_n", $approved_value, "s"); // use if $override_default_start_values is true
            $self_redirect_link = "main.php"; // the relative file path of the page
            //
            $last_page = $_GET["last_page_displayed"];// retrieve this form input for pagination view
            if (empty($last_page)) {
                $last_page = 0;
            }
            $search_input = check_and_replace_if_variable_is_empty(trim_spaces_from_string($_GET["search_terms"])); // retrieve this input for pagination view
            $seperated_search_terms = return_seperated_alnum_chars($search_input);
            //
            include("./search_display_module.php");
        ?>
    </div>
</body>
</html>
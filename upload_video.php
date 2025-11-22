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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Video</title>
    <link rel="stylesheet" href="./css/common_element_classes.css">
    <link rel="stylesheet" href="./css/colours.css">
    <link rel="stylesheet" href="/css/upload_video_css.css">
</head>
<body>
    <div class="page_content_container">
        <h1 class="primary_page_title">Upload Video</h1>
        <p class="info_text" id="global_variable_info_text"></p> <!--text to display if certain global control variables are active-->
        <p class="info_text" id="return_info_text"></p> <!--text to display, returned from the upload processing page if certain conditions are active-->
        <form action="" method="post" enctype="multipart/form-data">
            <div id="oneoff_input">
                <div id="sbs_section">
                    <label id="sbs_input_label" for="upload_file">Upload File</label>
                    <input type="file" name="upload_file" id="upload_file">
                </div>
                <H3 id="upload_start_indicator">File upload in progress</H3>
                <p id="upload_error"></p>
            </div>
            <div class="input_combo">
                <label for="video_title">Video Title</label>
                <textarea id="video_title" name="video_title" rows="1" cols="1000"></textarea>
                <p id="title_error"></p>
            </div>
            <div class="input_combo">
                <label style="vertical-align: top;" for="video_description">Video Description</label>
                <textarea id="video_description" name="video_description" rows="10" cols="1000"></textarea>
                <p id="description_error"></p>
            </div>
            <input id="form_submit" class="form_submit_button" type="submit" value="Upload">
            <a class="link_element" href="./manage_account.php">Back</a>
        </form>
    </div>
</body>
</html>
<script src="./js/upload_video_js.js"></script>
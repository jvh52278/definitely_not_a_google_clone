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
    <title>Upload Processing</title>
    <link rel="stylesheet" href="./css/colours.css">
</head>
<body>
    <h1 id="title_header">Your upload is being processed</h1>
    <img id="body_image" src="./images/please_accept_this_drawing_of_a_spider_as_payment.png" alt="a very old and very specific reference">
    <?php
        header("refresh:5;url=./upload_video.php"); #debug
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
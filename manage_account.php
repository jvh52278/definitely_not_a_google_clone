<?php
    session_start();
    // if a user is not logged in, redirect back to login page
    if ($_SESSION["logged_in"] != true) {
        header("Location: ./login.php");
    }
    include "./database_access_functions.php";
    include "./common_utility_functions.php";
    $user_info_retrieval = $database_access_object->prepared_statment_select_on_one_record("users", "user_id", $_SESSION["logged_in_user"], "s");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Account</title>
    <link rel="stylesheet" href="./css/colours.css">
    <link rel="stylesheet" href="./css/manage_account_css.css">
    <link rel="stylesheet" href="./css/common_element_classes.css">
</head>
<body>
    <div id="page_contents">
        <h1 id="section_title">Manage Account</h1>
        <h2 class="primary_page_title">Welcome <?php echo $user_info_retrieval[0]["user_name"]; ?></h2>
        <div class="center_container"><a href="./change_password.php">Change Password</a></div>
        <div class="center_container"><a href="">Upload Video</a></div>
        <div class="center_container"><a href="">Manage uploads</a></div>
        <!-- show the approve link if the user is admin -->
        <?php
        if ($_SESSION["is_admin"] == "y") {
            echo '<div class="center_container"><a href="">Approve User Uploads</a></div>';
        }
        ?>
        <div class="center_container"><a href="./main.php">Return to homepage</a></div>
        <div class="center_container"><a href="./logout_processing.php">Logout</a></div>
        <!-- 
        to do:
        change password function - use $_SESSION["logged_in_user"] to change the user password
        logout function - set $_SESSION["logged_in_user"], $_SESSION["logged_in"] and $_SESSION["is_admin"] to blank
        -->
    </div>
</body>
</html>
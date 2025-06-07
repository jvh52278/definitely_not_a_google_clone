<?php
    include "./database_access_functions.php";
    include "./common_utility_functions.php";
    session_start();
    // if a user is not logged in, redirect back to login page
    if ($_SESSION["logged_in"] != true) {
        header("Location: ./login.php");
    }
    // retrieve post inputs
    $success_message_recieved = check_and_replace_if_variable_is_empty($_POST["success_message"]);
    $current_password_error_recieved = check_and_replace_if_variable_is_empty($_POST["current_password_error_message"]);
    $new_password_error_recieved = check_and_replace_if_variable_is_empty($_POST["new_password_error_message"]);
    $retype_password_error_recieved = check_and_replace_if_variable_is_empty($_POST["retype_password_error_message"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change password</title>
    <link rel="stylesheet" href="./css/colours.css">
    <link rel="stylesheet" href="./css/common_element_classes.css">
    <link rel="stylesheet" href="./css/change_password_css.css">
</head>
<body>
    <div class="page_content_container">
        <h1 class="primary_page_title">Change password</h1>
        <h2 id="success_message"><?php echo $success_message_recieved ?></h2> <!-- success message -->
        <div id="form_section">
            <form id="password_change_form" action="./password_change_processing.php" method="post">
                <!-- input for current password -->
                <div class="input_combo">
                    <label for="old_password">Current password</label>
                    <input type="text" name="old_password" id="old_password">
                    <p><?php echo $current_password_error_recieved ?></p> <!-- current password error -->
                </div>
                <!-- input for new password -->
                <div class="input_combo">
                    <label for="new_password">New password</label>
                    <input type="text" name="new_password" id="new_password">
                    <p><?php echo $new_password_error_recieved ?></p> <!-- new password error -->
                </div>
                <!-- input for new password confirmation -->
                <div class="input_combo">
                    <label for="confirm_new_password">Retype new password</label>
                    <input type="text" name="confirm_new_password" id="confirm_new_password">
                    <p><?php echo $retype_password_error_recieved ?></p> <!-- new password confirmation error -->
                </div>
                <!-- form submit button -->
                 <input class="form_submit_button" type="submit" value="Change password">
            </form>
        </div>
        <a class="link_element" href="./manage_account.php">Back</a>
    </div>
</body>
</html>
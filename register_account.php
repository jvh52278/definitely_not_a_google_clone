<!-- link the database access functions -->
<?php
    include "./database_access_functions.php";
    include "./common_utility_functions.php";
    $display_username_error = check_and_replace_if_variable_is_empty($_POST["username_error"]);
    $dispay_password_error = check_and_replace_if_variable_is_empty($_POST["password_error"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./css/colours.css">
    <link rel="stylesheet" href="./css/register_account_css.css">
</head>
<body>
    <!-- div containing the page contents -->
    <div id="page_contents">
        <!-- the header to the registration section -->
        <h1 class="text_element" id="register_user_header">Create an account</h1>
        <!-- form containing the user registration form -->
        <form action="./register_account_processing.php" id="user_registration_form" name="user_registration_form" method="post">
            <!-- div containing label-input combo for username-->
            <div class="input_combo">
                <!-- label for the username input -->
                <label for="username">Username</label>
                <!-- input for the username-->
                <input type="text" name="username" id="username">
                <!-- username field error message -->
                <p><?php echo $display_username_error; ?></p>
            </div>
            <!-- div containing label-input combo for password -->
            <div class="input_combo">
                <!-- label for the password input -->
                <label for="password">Password</label>
                <!-- input for the password-->
                <input type="text" name="password" id="password">
                <!-- password field error message -->
                <p><?php echo $dispay_password_error; ?></p>
            </div>
            <!-- form submit button -->
            <input type="submit" name="user_register_form_submit" id="user_register_form_submit" value="Create Account">
            <!-- div containing return to login link -->
            <div class="link_section">
                <!-- link to return to login -->
                <a id="return_to_login" href="./login.php">Return to login</a>
                <!-- link to return to homepage -->
                <a id="return_to_homepage" href="./main.php">Return to homepage</a>
            </div>
        </form>
        <!-- link to return back to login -->
    </div>
</body>
</html>
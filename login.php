<!-- link the database access functions -->
<?php
    include "./database_access_functions.php";
    include "./common_utility_functions.php";
    $display_login_error = check_and_replace_if_variable_is_empty($_POST["login_error"]);
    session_start();
    // if a user is already logged in, redirect to the account managment page
    if ($_SESSION["logged_in"] == true) {
        header("Location: ./manage_account.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="./css/colours.css">
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <div id="page_contents">
        <h1 style="text-align: center; color: var(--primary_text_color_1)">Login</h1>
        <!--the user login form-->
        <form action="./login_processing.php" method="post" id="login_form" name="login_form">
            <!--username input-->
            <div class="input_combo">
                    <!-- label for the username input -->
                    <label for="username">Username</label>
                    <!-- input for the username-->
                    <input type="text" name="username" id="username">
            </div>
            <!-- div containing label-input combo for password -->
            <div class="input_combo">
                <!-- label for the password input -->
                <label for="password">Password</label>
                <!-- input for the password-->
                <input type="password" name="password" id="password">
                <!-- login error message -->
                 <p><?php echo $display_login_error; ?></p>
            </div>
            <!-- form submit button -->
            <input type="submit" name="login_submit" id="login_submit" value="Login">
        </form>
        <div class="link_section">
            <a href="./main.php">Return to homepage</a> <!-- link to return to homepage -->
            <a href="./register_account.php">Create account</a> <!-- link to user registeration page -->
        </div>
    </div>
</body>
</html>
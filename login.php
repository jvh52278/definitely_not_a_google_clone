<!-- link the database access functions -->
<?php
    include "./database_access_functions.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <div id="page_contents">
        <h1 style="text-align: center;">Login</h1>
        <!--the user login form-->
        <form action="" method="post" id="login_form" name="login_form">
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
                <input type="text" name="password" id="password">
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
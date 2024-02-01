<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <link rel="stylesheet" href="./css/register_account_success_css.css">
</head>
<body>
    <!-- div containing the success message -->
    <div id="message_contents">
        <h1>Registration successful</h1>
        <h2>you will be redirected back to the login page</h2>
    </div>
    <?php
        header("refresh:2;url=./login.php");
    ?>
</body>
</html>
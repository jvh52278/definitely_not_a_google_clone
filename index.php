<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing page</title>
    <link rel="stylesheet" href="css/index_css.css">
</head>
<body>
    <h1>Test message</h1>
    <h2>This will be blank for now</h2>
    <a href="./main.php">test link</a>
    <?php
        // setup session variable to enable login
        session_start();
        $_SESSION["logged_in"] = false;
        // temporarily redirect to youtube clone main page
        header("Location: ./main.php")
    ?>
</body>
</html>

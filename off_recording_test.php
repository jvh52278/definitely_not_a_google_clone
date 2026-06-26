<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home page</title>
</head>
<body>
    <h1>testing event log</h1>
    <?php
        session_start();
        include("./database_access_functions.php");
        include("./storage_io_check_module.php");
        include("./event_log_module.php");
    ?>
</body>
</html>
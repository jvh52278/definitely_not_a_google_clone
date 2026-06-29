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
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        session_start();
        include("./database_access_functions.php");
        include("./storage_io_check_module.php");
        include("./common_utility_functions.php");

        print_debug_test_value($_SERVER['REMOTE_ADDR'], "black");

        $event_type = "user access";
        $event_value = strval($_SERVER['REMOTE_ADDR']); // strval($_SERVER['REMOTE_ADDR'])
        include("./event_log_module.php");

        $event_log_contents = $database_access_object->retrieve_all_records_from_table("activity_log");
        var_dump($event_log_contents);
    ?>
</body>
</html>
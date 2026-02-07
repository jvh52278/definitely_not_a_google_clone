<?php
    session_start();
    include "./database_access_functions.php";
    include "./common_utility_functions.php";
    $user_info_retrieval = $database_access_object->prepared_statment_select_on_one_record("users", "user_id", $_SESSION["logged_in_user"], "s");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/colours.css">
    <link rel="stylesheet" href="./css/common_element_classes.css">
</head>
<body>
    <div id=menu_section>
        <p>vote bar placeholder</p>
    </div>
</body>
</html>
<style>
    * {
        box-sizing: border-box;
    }
    body {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        border: none;
    }
    #menu_section {
        margin-top: 0;
        position: sticky;
        margin-bottom: 0;
        margin-left: 0;
        margin-right: 0;
        background-color: orange;
        padding: 0;
        box-sizing: border-box;
        border: none;
    }
   
</style>

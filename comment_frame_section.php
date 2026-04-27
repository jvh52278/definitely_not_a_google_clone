<?php
    session_start();
    include "./database_access_functions.php";
    include "./common_utility_functions.php";
    $comment_data_retrieval = $database_access_object->prepared_statment_select_on_one_record("comments", "associated_video_id", $_SESSION["current_video"], "s");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments</title>
    <link rel="stylesheet" href="./css/colours.css">
</head>
<body>
    <form id="comment_form" action="./comment_processing.php" method="post">
        <textarea name="comment_text" id="comment_text"></textarea>
        <div id="comment_interation_section">
            <input type="submit" value="post comment" id="post_comment_button" name="post_comment_button">
            <a id="refresh_link" href="./comment_frame_section.php">refresh</a>
        </div>
    </form>
    <?php
    //display comments
    print_debug_test_value(var_dump($comment_data_retrieval), "white");
    ?>
</body>
</html>
<style>
    body {
        background-color: var(--primary_page_background);
    }
    h1 {
        color: var(--primary_text_color_1);
    }
    
    h2 {
        color: var(--primary_text_color_1);
    }

    h3 {
        color: var(--primary_text_color_1);
    }
    h4 {
        color: var(--primary_text_color_1);
    }
    p {
        color: var(--primary_text_color_1);
    }
    a {
        color: var(--primary_text_color_1);
    }
    label {
        color: var(--primary_text_color_1);
    }
    #comment_text {
        width: 100%;
        background-color: var(--secondary_matching_color);
        font-family: Sans-Serif;
        color: var(--primary_text_color_1);
        border: none;
    }
    #comment_interation_section {
        margin-top: 5px;
        margin-bottom: 5px;
    }
    #post_comment_button {
        font-family: Sans-Serif;
        color: var(--primary_text_color_1);
        background-color: var(--primary_page_background);
        border: solid;
        border-color: var(--primary_text_color_1);
        border-width: 2px;
        margin-right: 5px;
        font-size: 15px;
        padding: 5px;
    }
    #refresh_link {
        font-family: Sans-Serif;
        color: var(--primary_text_color_1);
        background-color: var(--primary_page_background);
        border: solid;
        border-color: var(--primary_text_color_1);
        border-width: 2px;
        text-decoration: none;
        font-size: 15px;
        padding: 5px;
    }
    #comment_form {
        position: sticky;
        top: 0;
    }
</style>
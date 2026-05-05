<?php
    session_start();
    include "./database_access_functions.php";
    include "./common_utility_functions.php";
    $user_info_retrieval = $database_access_object->prepared_statment_select_on_one_record("users", "user_id", $_SESSION["logged_in_user"], "s");
    $video_info_retrieval = $database_access_object->prepared_statment_select_on_one_record("videos", "video_id", $_SESSION["current_video"], "s");
?>
<!DOCTYPE html>
<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8">-->
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
        <!--upvote section-->
        <div class="tp_display">
            <form action="./upvote_processing.php" method="post" class="tp_side_form">
                <input type="text" name="vote_count" id="vote_count" hidden>
                <input type="submit" value="&#11014" class="tp_interactive_icon">
            </form>
            <p class="tp_middle"><?php echo $video_info_retrieval[0]["upvotes"]; ?></p>
            <form action="./upvote_processing.php" method="post" class="tp_side_form">
                <input type="text" name="vote_count" id="vote_count" hidden>
                <input type="submit" value="&#11014" class="tp_interactive_icon_left">
            </form>
        </div>
        <!--downvote section-->
        <div class="tp_display">
            <form action="./downvote_processing.php" method="post" class="tp_side_form">
                <input type="text" name="vote_count" id="vote_count" hidden>
                <input type="submit" value="&#11015" class="tp_interactive_icon">
            </form>
            <p class="tp_middle"><?php echo $video_info_retrieval[0]["downvotes"]; ?></p>
            <form action="./downvote_processing.php" method="post" class="tp_side_form">
                <input type="text" name="vote_count" id="vote_count" hidden>
                <input type="submit" value="&#11015" class="tp_interactive_icon_left">
            </form>
        </div>
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
        background-color: var(--primary_page_background);
        border-style: solid;
        border-width: 2px;
        border-color: var(--secondary_matching_color);
        padding: 5px;
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .tp_display {
        display: flex;
        margin-top: 5px;
        margin-bottom: 5px;
    }
    .tp_side_form {
        flex: 2;
    }
    .tp_middle {
        flex: 10;
        margin: 0;
        text-align: center;
        /*
        border-style: solid;
        border-top: none;
        border-bottom: none;
        border-width: 2px;
        border-color: var(--primary_text_color_1);
        */
    }
    .tp_interactive_icon {
        color: var(--primary_text_color_1);
        border-color: var(--primary_text_color_1);
        background-color: var(--primary_page_background);
    }
    .tp_interactive_icon:hover {
        color: var(--element_hover_text_color);
        border-color: var(--element_hover_text_color);
        background-color: var(--element_hover_background_color);
    }
    .tp_interactive_icon_left {
        color: var(--primary_text_color_1);
        border-color: var(--primary_text_color_1);
        background-color: var(--primary_page_background);
        display: block;
        margin-right: 0;
        margin-left: auto;
    }
    .tp_interactive_icon_left:hover {
        color: var(--element_hover_text_color);
        border-color: var(--element_hover_text_color);
        background-color: var(--element_hover_background_color);
    }
    p {
        color:  var(--primary_text_color_1);
    }
   
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You have logged out</title>
    <link rel="stylesheet" href="./css/colours.css">
</head>
<body>
    <div id="message_box">
        <h1>You have logged out</h1>
    </div>
    <?php
        header("refresh:3; url=./main.php");
    ?>
</body>
</html>
<style>
    body {
        background-color: var(--primary_page_background);
    }
    #message_box {
        background-color: var(--primary_element_background);
    }
    #message_box h1 {
        color: var(--primary_text_color_2);
        text-align: center;
        padding: 10px;
    }
</style>
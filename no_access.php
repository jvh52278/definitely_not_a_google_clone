<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>This video has not been made public</title>
    <link rel="stylesheet" href="./css/colours.css">
    <link rel="stylesheet" href="./css/common_element_classes.css">
</head>
<body>
    <h1 id="message">This video has not been made public</h1>
    <img id="image" src="./images/lolwut.png" alt="an old meme">
    <?php 
        header("refresh:5;url=./main.php");
    ?>
</body>
</html>
<style>
    #message {
        color: var(--primary_text_color_2);
        background-color: var(--primary_element_background);
        text-align: center;
        padding: 10px;
    }
    #image {
        display: block;
        width: 70%;
        margin-left: auto;
        margin-right: auto;
        border-style: solid;
        border-width: 3px;
        border-color: var(--primary_border_color_1);
    }
    body {
        background-color: var(--primary_page_background);
    }
</style>
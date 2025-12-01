<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Complete</title>
    <link rel="stylesheet" href="./css/colours.css">
</head>
<body>
    <h1 id="title_header">Your upload has been processed<br>You will be redirected back to the upload page in 5 seconds</h1>
    <img id="body_image" src="./images/please_accept_this_drawing_of_a_spider_as_payment.png" alt="a very old and very specific reference">
    <?php
        header("refresh:5;url=./upload_video.php");
    ?>
</body>
</html>
<style>
    body {
        background-color: var(--primary_page_background);
    }
    #title_header {
        background-color: var(--primary_element_background);
        color: var(--primary_page_background);
        width: 100%;
        text-align: center;
        padding: 20px;
    }
    #body_image {
    width: 60%;
    margin-left: auto;
    margin-right: auto;
    margin-top: 10px;
    margin-bottom: 10px;
    display: block;
    }
</style>
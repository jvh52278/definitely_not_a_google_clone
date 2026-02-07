<?php
    session_start();
    include "./database_access_functions.php";
    include "./common_utility_functions.php";
    $user_info_retrieval = $database_access_object->prepared_statment_select_on_one_record("users", "user_id", $_SESSION["logged_in_user"], "s");
    $video_id = $_GET["video_id"];
    // retrieve video details
    $video_info = $database_access_object->prepared_statment_select_on_one_record("videos", "video_id",$video_id, "s");
    // if the video id is not valid, redirect away
    if (count($video_info) == 0) {
        header("Location: ./not_found.php");
    }
    //
    $video_title = $video_info[0]["title"];
    $video_description = $video_info[0]["description"]; // description
    $video_uploader_id = $video_info[0]["uploader"]; // uploader
    $video_upload_date = $video_info[0]["upload_date"]; // upload_date
    $human_readable_date = date('m-d-Y H:i:s T', $video_upload_date);
    $video_file_original = $video_info[0]["path_to_video_file"]; // path_to_video_file
    $video_file_alt = $video_info[0]["path_to_video_file_alt"]; // path_to_video_file_alt
    $thumbnail = $video_info[0]["path_to_thumbnail"]; // path_to_thumbnail
    $upload_approved = $video_info[0]["upload_approved_y_n"]; // upload_approved_y_n
    // if the video is not approved, redirect away
    if ($upload_approved != "y" && count($video_info) == 1) {
        header("Location: ./no_access.php");
    }
    // retrieve the username of the uploader
    $uploader_info = $database_access_object->prepared_statment_select_on_one_record("users", "user_id", $video_uploader_id, "s");
    $uploader_username = $uploader_info[0]["user_name"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $video_title ?></title>
    <link rel="stylesheet" href="./css/colours.css">
    <link rel="stylesheet" href="./css/video_css.css">
    <link rel="stylesheet" href="./css/common_element_classes.css">
</head>
<body>
    <?php include("./common_header.php") ?>
    <video id="video_section" controls>
        <source src="<?php echo $video_file_original ?>">
        <source src="<?php echo $video_file_alt ?>">
    </video>
    <div id="info_section">
        <iframe src="./vote_bar.php" frameborder="0"></iframe>
        <h2><?php echo $video_title ?></h2>
        <h4>uploaded by <p style="display: inline-block; text-decoration: underline;"><?php echo $uploader_username ?></p> on <?php echo $human_readable_date ?></h4>
        <p><?php echo $video_description ?></p>
    </div>
    <div id="recommendation_section">
        <h1 style="background-color: black;">Recomendation section placeholder</h1>
    </div>
    <div id="comments_section">
        <h1 style="background-color: black;">Comments section placeholder</h1>
    </div>
</body>
</html>
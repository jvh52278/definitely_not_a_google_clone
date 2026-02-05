<?php
session_start();
// if a user is not logged in, redirect back to login page
if ($_SESSION["logged_in"] != true) {
    header("Location: ./login.php");
}
include("./database_access_functions.php");
include("./common_utility_functions.php");
include("./global_control_variables.php");
$user_info_retrieval = $database_access_object->prepared_statment_select_on_one_record("users", "user_id", $_SESSION["logged_in_user"], "s");
// retrieve form inputs
$input_video_title = trim_spaces_from_string($_POST["video_title"]);
$input_video_description = trim_spaces_from_string($_POST["video_description"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Processing</title>
    <link rel="stylesheet" href="./css/colours.css">
</head>
<body>
    <h1 id="title_header">Your upload is being processed</h1>
    <img id="body_image" src="./images/please_accept_this_drawing_of_a_spider_as_payment.png" alt="a very old and very specific reference">
    <!--<p style="color: white;"></p>-->
    <?php
        $force_return_via_upload_limiter = false; // if true, and the upload limit has been reached, redirect back to upload page if randomly selected

        $path_to_temporary_upload_file = $_FILES['upload_file']['tmp_name'];
        $upload_file_extention = pathinfo($_FILES['upload_file']["name"],PATHINFO_EXTENSION);
        $full_file_path_to_upload_files = $path_to_temporary_upload_file.".".$upload_file_extention;

        $form_inputs_are_valid = false;
        $preprocessing_checks_are_valid = false; // virus scanning, number of pre-existing uploads
        $primary_checks_are_valid = false ;// file size, video length, video format
        
        $top_message_status = 0;
        $upload_error_status = 0;
        $title_error_status = 0;
        $description_error_status = 0;

        $video_file_size = "";
        $video_length = "";
        $video_aspect_ratio = "";

        $cpu_usage_calulation = "bash ".$document_root."/"."these_files_should_be_hidden/cpu_usage_calculation.sh";
        $current_cpu_usage = "";

        $document_root = $_SERVER['DOCUMENT_ROOT'];
        
        // preprocessing checks
        $cpu_usage_does_not_exceed_maximum = false;
        if ($force_cpu_usage_state == false) {
            $cpu_usage_does_not_exceed_maximum = true;
        } else {
            // check cpu usage
            $current_cpu_usage = shell_exec($cpu_usage_calulation);
            $comparison_value = (float) $current_cpu_usage;
            if ($comparison_value <= $enforced_cpu_use_limit) {
                $cpu_usage_does_not_exceed_maximum = true;
            } else {
                $upload_error_status = 7;
            }
        }
        if ($cpu_usage_does_not_exceed_maximum = true) {
            if ($force_preprocessing_virus_scan == false && $force_upload_limiter == false) {
                $preprocessing_checks_are_valid = true;
            } else {
                if ($force_preprocessing_virus_scan == true) {
                    if ($_FILES['upload_file']['error'] == 0) {
                        $virus_scan_command = "clamscan -rz $path_to_temporary_upload_file | grep 'Infected files'";
                        $virus_scan_good_return = false;
                        $virus_scan_output = trim(shell_exec($virus_scan_command));
                        $expected_virus_scan_results = "Infected files: 0";
                        // -- virus scan
                        if ($virus_scan_output == $expected_virus_scan_results) {
                            $virus_scan_good_return = true;
                        } else {
                            $upload_error_status = 6;
                        }
                        if ($virus_scan_good_return == true) {
                            $preprocessing_checks_are_valid = true;
                        }
                    } else {
                        $upload_error_status = 1;
                    }
                }
                // -- user upload count
            }
        }
        // check if form inputs are valid
        if ($preprocessing_checks_are_valid == true) {
            // -- check if the upload field is blank -> that a file was uploaded
            $upload_field_is_not_blank = false;
            if ($_FILES['upload_file']['error'] == 0) {
                $upload_field_is_not_blank = true;
                // check if the uploaded file is a video
                $verification_command_1 = "ffprobe -v error -select_streams v:0 -count_packets -show_entries stream=nb_read_packets -of csv=p=0 $path_to_temporary_upload_file";
                $video_test_verification_output_1 = shell_exec($verification_command_1);
                try {
                    $test_output_1 = (int) $video_test_verification_output_1;
                    if ($test_output_1 <= 2) {
                        $upload_field_is_not_blank = false;
                        $upload_error_status = 5;
                    } else {
                        $file_size_command = "ffprobe -i $path_to_temporary_upload_file -show_entries format=size -v quiet -of csv='p=0'";
                        $video_file_size = shell_exec($file_size_command);
                        $video_length_command = "ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $path_to_temporary_upload_file";
                        $video_length = shell_exec($video_length_command);
                        $video_aspect_ratio_command = "ffprobe -v error -select_streams v:0 -show_entries stream=display_aspect_ratio -of csv=s=x:p=0 $path_to_temporary_upload_file";
                        $video_aspect_ratio = shell_exec($video_aspect_ratio_command);
                    }
                }
                catch (Exception $e) {
                    $upload_field_is_not_blank = false;
                    $upload_error_status = 5;
                }
                //print_debug_test_value($current_cpu_usage, "white");
            } else {
                $upload_error_status = 1;
            }
            // -- check if the title field is blank
            $title_is_not_blank = false;
            if (!empty($input_video_title)) {
                $title_is_not_blank = true;
            } else {
                $title_error_status = 1;
            }
            // -- check if the description field is blank
            $description_is_not_blank = false;
            if (!empty($input_video_description)) {
                $description_is_not_blank = true;
            } else { 
                $description_error_status = 1;
            }
            if ($upload_field_is_not_blank == true && $title_is_not_blank == true && $description_is_not_blank == true) {
                $form_inputs_are_valid = true;
            }
        }
        // primary checks, if applicable
        if ($form_inputs_are_valid == true) {
            // check file size
            $correct_file_size = false;
            // check video length
            $correct_video_length = false;
            // check video format
            $correct_video_format = false;
            // input correction from global control variables
            if ($force_efficient_file_size == false) {
                $correct_video_length = true;
                $correct_file_size = true;
            } else {
                // if not overidden, check video file size and video length
                $filesize_comparison_value = (float) $video_file_size;
                $video_length_comparison_value = (float) $video_length;

                if ($filesize_comparison_value <= $enforced_max_file_size) {
                    $correct_file_size = true;
                } else {
                    $upload_error_status = 2;
                }
                if ($video_length_comparison_value <= $enforced_max_video_length) {
                    $correct_video_length = true;
                } else {
                    $upload_error_status = 3;
                }
                //print_debug_test_value($upload_error_status, "white");
            }
            if ($force_16_9_mp4_format == false) {
                $correct_video_format = true;
            }
            if ($correct_file_size == true && $correct_video_length == true && $correct_video_format == true) {
                $primary_checks_are_valid = true;
            }
        }

        if ($form_inputs_are_valid == true && $preprocessing_checks_are_valid == true && $primary_checks_are_valid == true) {
            header("Location: ./upload_success.php");
        } else {
            // redirect back with errors
            header("Location: ./upload_video.php?top_message_code=$top_message_status&upload_message_code=$upload_error_status&title_message_code=$title_error_status&description_message_code=$description_error_status");
            //header("refresh:7;url=./upload_video.php?top_message_code=$top_message_status&upload_message_code=$upload_error_status&title_message_code=$title_error_status&description_message_code=$description_error_status");
        }
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
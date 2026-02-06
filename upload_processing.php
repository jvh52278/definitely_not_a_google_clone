<?php
// show errors
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//
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
        $video_format = "";

        $document_root = $_SERVER['DOCUMENT_ROOT'];
        $cpu_usage_calulation = "bash ".$document_root."/"."these_files_should_be_hidden/cpu_usage_calculation.sh";
        $current_cpu_usage = "";

        
        
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
                        $video_aspect_ratio = trim(shell_exec($video_aspect_ratio_command));
                        $video_format_command = "mediainfo $path_to_temporary_upload_file | grep 'MPEG-4' | cut -d ':' -f 2 | xargs";
                        $video_format = trim(shell_exec($video_format_command));
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
                //print_debug_test_value($video_format, "white");
            }
            if ($force_16_9_mp4_format == false) {
                $correct_video_format = true;
            } else {
                // if not overidden, check video format and aspect ratio
                $is_16_9_or_9_16 = false;
                $is_mp4 = false;
                if ($video_aspect_ratio == $enforced_video_aspect_ratio || $video_aspect_ratio == $enforced_video_aspect_ratio_alt) {
                    $is_16_9_or_9_16 = true;
                }
                if ($video_format == $enforced_video_file_ext) {
                    $is_mp4 = true;
                }
                if ($is_16_9_or_9_16 == true && $is_mp4 == true) {
                    $correct_video_format = true;
                } else {
                    $upload_error_status = 4;
                }
            }
            if ($correct_file_size == true && $correct_video_length == true && $correct_video_format == true) {
                $primary_checks_are_valid = true;
            }
        }

        if ($form_inputs_are_valid == true && $preprocessing_checks_are_valid == true && $primary_checks_are_valid == true) {
            $unique_key_found = false;
            $unique_key_to_insert = ""; // -> insert into database as video id
            while ($unique_key_found == false) {
                $unique_key_value = $database_access_object->create_random_string();
                $database_check_results = $database_access_object->prepared_statment_select_on_one_record("videos","video_id",$unique_key_value,"s");
                if (count($database_check_results) == 0) {
                    $unique_key_to_insert = $unique_key_value;
                    $unique_key_found = true;
                }
            }
            $save_file_name = trim(replace_spaces($input_video_title));
            $file_ext = pathinfo($_FILES['upload_file']["name"],PATHINFO_EXTENSION);
            $complete_file_name = $unique_key_to_insert.$save_file_name.".".$file_ext; // the filename of the original upload 
            $alt_file_name = "DS_".$unique_key_to_insert.$save_file_name.".".$file_ext; // the downscaled file name, if the file exists
            $original_upload_path_to_insert = "/"."uploads"."/".$complete_file_name; // -> insert into database
            $alt_file_path_to_insert = "/"."uploads"."/".$alt_file_name; // -> insert into database
            $full_path_to_saved_file = $document_root."/"."uploads"."/".$complete_file_name;
            // save upload
            # filename is random_string + title_with_spaces_replaced + file_ext
            move_uploaded_file($_FILES["upload_file"]["tmp_name"],$full_path_to_saved_file);
            // auto generate thumbnail
            # try first to get screenshot a 5 second mark, if that fails then get screenshot at 00:00:00
            $full_thumbnail_path = $document_root."/"."thumbnails"."/".$unique_key_to_insert."_tm_.png"; // -> insert into database
            $path_to_upload_directory = $document_root."/"."thumbnails"."/";
            $thumbnail_file_name = $unique_key_to_insert."_tm_.png";
            $thumbnail_path_to_insert = "/"."thumbnails"."/".$thumbnail_file_name;
            $screenshot_attempt_1 = "ffmpeg -i $full_path_to_saved_file -ss 00:00:10 -vframes 1 $full_thumbnail_path";
            $screenshot_attempt_2 = "ffmpeg -i $full_path_to_saved_file -ss 00:00:00 -vframes 1 $full_thumbnail_path";
            try {
                shell_exec($screenshot_attempt_1);
                $verification_step = "ls $path_to_upload_directory | grep $thumbnail_file_name | wc -l";
                $verification_result = shell_exec($verification_step);
                $converted_verification_result = (int) $verification_result;
                if ($converted_verification_result != 1) {
                    shell_exec($screenshot_attempt_2);
                }
            }
            catch (Exception $e) {
                shell_exec($screenshot_attempt_2);
            }
            // create database record
            $moderation_status = "n";
            if ($user_info_retrieval[0]["is_admin_y_n"] == "y") {
                $moderation_status = "y";
            }
            if ($moderation_status == "n") {
                if ($set_approved_status_to_true_default == true) {
                    $moderation_status = "y";
                }
            }
            // ### prepare access and customization variables ###
            // database access variables
            $database_user = $ref_database_username; // the database user -- user name
            $database_user_password = $ref_database_user_password; // the password of the database user
            $database_name = $ref_database_name; // the name of the database that is being accessed
            $sql_server_name = $ref_server_name; // is usually localhost
            // query customization variables -- to define fixed parts of the query
            $table_to_access = "videos";
            //$identifying_column = "name_of_column_containing_identifying_records";
            // query customization variables -- for the dynamic / user input 
                // this is the ?
            //$identifying_record = value_of_identifying_record;
            $identifying_record_data_type = "ssssissssii";
                // identifying_record_datatype: i -> int
                // identifying_record_datatype: d -> float
                // identifying_record_datatype: s -> string
                // identifying_record_datatype: b -> blob, sent in packets
            // ### run the sql query ###
            $database_connection = new mysqli($sql_server_name, $database_user, $database_user_password, $database_name);
            // step 1A: prepare the query
                // create the query, but put a ? where a user / dynamic input would be
            $sql_query = "INSERT INTO $table_to_access (video_id, title, description, uploader, upload_date, path_to_video_file, path_to_video_file_alt, path_to_thumbnail, upload_approved_y_n, upvotes, downvotes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $prepared_sql_query = $database_connection->prepare($sql_query);
            // step 1B: bind the user / dynamic input variables to fixed data types
                // bind_param('|input_1_datatype|input_2_datatype|....', $input_1, $input_2, .....)
                // NOTE: the inputs go in order that they would appear in the query, left to right, where the ? are
                // datatype: i -> int
                // datatype: d -> float
                // datatype: s -> string
                // datatype: b -> blob, sent in packets
            $upload_date = time();
            $zero_value = 0;
            $prepared_sql_query->bind_param($identifying_record_data_type, $unique_key_to_insert, $input_video_title, $input_video_description, $_SESSION["logged_in_user"], $upload_date, $original_upload_path_to_insert, $alt_file_path_to_insert, $thumbnail_path_to_insert, $moderation_status, $zero_value, $zero_value);
            // step 2A: execute the prepared query
            $prepared_sql_query->execute(); // NOTE: this does not return the result, if any exists
            // step 2B: retrieve the results of the query and put them into an array
            //$query_results = $prepared_sql_query->get_result()->fetch_all(MYSQLI_ASSOC);
            //echo count($query_results);
            // close the prepared query
            $prepared_sql_query->close();

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
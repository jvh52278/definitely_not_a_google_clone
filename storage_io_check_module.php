<?php
    //include("./database_access_functions.php");
    $files_in_upload_directory = scandir("./uploads");
    $enforced_max_total_storage_space_used = 1000*1000*1000*175;
    //$enforced_max_total_storage_space_used = 1;
    $total_size_of_upload_directory = 0; // in bytes

    for ($x = 0; $x < count($files_in_upload_directory); $x = $x + 1) {
        // delete any files that don't have a database entry
        $current_file = $files_in_upload_directory[$x];
        $file_check_path = "/uploads/".$current_file;
        $original_upload_check = $database_access_object->prepared_statment_select_on_one_record("videos", "path_to_video_file", $file_check_path, "s");
        $alt_file_check = $database_access_object->prepared_statment_select_on_one_record("videos", "path_to_video_file_alt", $file_check_path, "s");
        $file_in_database = false;
        $pcf = 0;
        if (!empty($current_file) && $current_file[0] != ".") {
            if (count($original_upload_check) == 1 || count($alt_file_check) == 1) {
                $file_in_database = true;
            }
            if ($file_in_database == false) {
                $file_delete_path = $_SERVER['DOCUMENT_ROOT'].$file_check_path;
                try {
                    unlink($file_delete_path);
                }
                catch (Exception $e) {
                    $pcf = $pcf + 1;
                }
            }
        }
        // get a count of the total file size
        $file_size = filesize("./uploads/$current_file");
        $total_size_of_upload_directory = $total_size_of_upload_directory + $file_size;
    }
?>
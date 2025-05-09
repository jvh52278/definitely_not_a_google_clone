<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home page</title>
</head>
<body>
    <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        // include the external class file
        include "./database_access_functions.php";
        // create an instance of the class
        $database_access_object = new database_access_object();
        // ## test set database access info
        $database_access_object->set_database_access_variables("sqladmin","sqladmin","youtube_clone","localhost");
        $test_value = $database_access_object->create_random_string();
        echo $test_value;
        // #####################################################
        // ## test retrieve all records no filtering
            /*
        $results_returned = $database_access_object->retrieve_all_records_from_table("videos");
        print_r($results_returned);
            */
        // ## test retrieve all records filtering by one item
            /*
        $results_returned = $database_access_object->prepared_statment_select_on_one_record("videos","video_id","1a","s");
        print_r($results_returned);
            */
        // ## test retrieve all records filtering by 2 items
            /*
        $results_returned = $database_access_object->prepared_statment_select_on_two_records("videos","video_id","2b","s","title","title 2","s");
        print_r($results_returned);
            */
        // ## test retrieve all records filting by 3 items
            /*
        $results_returned = $database_access_object->prepared_statment_select_on_three_records("videos","video_id","1a","s","title","title 1","s","description","description 1","s");
        print_r($results_returned);
            */
        // ## test update on one record
            /*
        $database_access_object->prepared_statment_update_on_one_record("videos","video_id","1a","s","path_to_video_file","new value 1","s");
            */
        // ## test update on two values
            /*
        $database_access_object->prepared_statment_update_on_two_records("videos","video_id","1a","s","title","title 1","s","path_to_video_file","new value 2","s");
            */
        // ## test update on three values
            /*
        $database_access_object->prepared_statment_update_on_three_records("videos","video_id","1a","s","title","title 1","s","description","description 1","s","path_to_video_file","new value 2","s");
            */
        // ## test delete on one record
            /*
        $database_access_object->prepared_statment_delete_on_one_record("videos","video_id","10j","s");
            */
        // ## test delete on two records
            /*
        $database_access_object->prepared_statment_delete_on_two_records("videos","video_id","11k","s","title","delete me 2","s");
            */
        // ## test delete on three records
            /*
        $database_access_object->prepared_statment_delete_on_three_records("videos","video_id","11a","s","title","delete me 3","s","description","delete me 3","s");
            */


    ?>
</body>
</html>
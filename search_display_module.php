<?php 
$full_search_mode = false; // if true, search for keywords in title, description and uploader name
$display_all = false; // if true, display all results in page view - 25 results per page, with a page counter and navigation bar at the bottom
$display_3_relevant_short = false; // if true, display 3 full search results, only the title and thumbnail 
$admin_moderation_view = $admin_moderation_mode_active; // if true, display the following items: title, description, thumbnail, uploader name, uploader date -> and results link to moderation page instead of the video player
$show_delete_option = $delete_option_active; // if true, show video delete button
$custom_results = $override_default_start_values; // if true (set before including this module), start the search/display process with a custom query (retrieved before this module)
/*
put this on every page that uses this module, change as needed
$seperated_search_terms = return_seperated_alnum_chars($search_term_input_value);
$display_mode_input = "full";
$override_default_start_values = false;
$delete_option_active = false;
$admin_moderation_mode_active = false;
$custom_start_results = array();
*/


$display_mode = $display_mode_input;
if ($display_mode == "full") {
    $full_search_mode = true;
}
if ($display_mode == "short") {
    $display_3_relevant_short = true;
}
if ($display_mode == "all") {
    $display_all = true;
}

try {
    $results_to_display = array();
    $search_terms_are_blank = false;
    if ($custom_results == true) {
        $results_to_display = $custom_start_results;
    }
    if ($full_search_mode == true || $display_3_relevant_short == true) {
        // matching arrays -> video_id to search result match percentage
        $initial_matching_videos = array();
        $inital_match_rate_percentage = array();
        //
        $individual_search_terms = $seperated_search_terms;
        if (count($individual_search_terms) == 0) {
            $search_terms_are_blank = true;
        }
        //var_dump(count($individual_search_terms));
        // get all upload info
        $all_video_records = array();
        if ($custom_results == false) {
            $all_video_records = $database_access_object->retrieve_all_records_from_table("videos");
        } else {
            $all_video_records = $results_to_display;
        }
        // loop through the video records and check for matches
        foreach ($all_video_records as $record_set) {
            $t_video_id = $record_set["video_id"];
            // get individual check values
            $t_title = $record_set["title"];
            //print_debug_test_value($t_title, "white");
            $t_description = $record_set["description"];
            //print_debug_test_value($t_description,"white");
            $t_uploader_id = $record_set["uploader"];
            $t_uploader_info = $database_access_object->prepared_statment_select_on_one_record("users","user_id",$t_uploader_id,"s");
            $t_uploader_username = $t_uploader_info[0]["user_name"];
            //print_debug_test_value($t_uploader_username, "white");
            //
            $values_to_match = count($individual_search_terms);
            // title match -> 50%
            $title_sub_match = 0;
            $title_matches_found = 0;
            for ($x = 0; $x < count($individual_search_terms); $x = $x + 1) {
                $current_value = $individual_search_terms[$x];
                if (check_if_string_contains_substring($t_title, $current_value)) {
                    $title_matches_found = $title_matches_found + 1;
                }
            }
            $title_sub_match = attempt_division($title_matches_found, $values_to_match);
            // description match -> 30%
            $description_sub_match = 0;
            $description_matches_found = 0;
            for ($x = 0; $x < count($individual_search_terms); $x = $x + 1) {
                $current_value = $individual_search_terms[$x];
                if (check_if_string_contains_substring($t_description, $current_value)) {
                    $description_matches_found = $description_matches_found + 1;
                }
            }
            $description_sub_match = attempt_division($description_matches_found, $values_to_match);
            // uploader name match -> 20%
            $uploader_sub_match = 0;
            $uploader_matches_found = 0;
            for ($x = 0; $x < count($individual_search_terms); $x = $x + 1) {
                $current_value = $individual_search_terms[$x];
                if (check_if_string_contains_substring($t_uploader_username,$current_value)) {
                    $uploader_matches_found = $uploader_matches_found + 1;
                }
            }
            $uploader_sub_match = attempt_division($uploader_matches_found, $values_to_match);
            if ($uploader_sub_match > 1) {
                $uploader_sub_match = 1;
            }
            // calculate total match percentage
            $total_match_percentage = $title_sub_match*0.5 + $description_sub_match*0.3 + $uploader_sub_match*0.2;
            //
            array_push($initial_matching_videos, $t_video_id);
            array_push($inital_match_rate_percentage, round($total_match_percentage, 2));
        }
        // sort the matching arrays for most relevant to least relevant
        //- make a copy of the percentage number match array
        //- remove 0 values from the copy
        $stage_2_sort_array = array();
        for ($x = 0; $x < count($inital_match_rate_percentage); $x = $x + 1) {
            $current_value = $inital_match_rate_percentage[$x];
            if ($current_value > 0) {
                $duplication_found = false;
                for ($y = 0; $y < count($stage_2_sort_array); $y = $y + 1) {
                    $check_value = $stage_2_sort_array[$y];
                    if ($check_value == $current_value) {
                        $duplication_found = true;
                    }
                }
                if ($duplication_found == false) {
                    array_push($stage_2_sort_array, $current_value);
                }
            }
        }
        //- sort from largest to smallest and remove duplicates
        //array_unique($stage_2_sort_array);
        rsort($stage_2_sort_array);
        //var_dump($stage_2_sort_array);
        $final_display_items = array();
        //- if the search terms are blank, show all videos
        if ($search_terms_are_blank == true) {
            foreach ($results_to_display as $record_row) {
                array_push($final_display_items, $record_row["video_id"]);
            }
        } else {
            foreach ($stage_2_sort_array as $ref_value) {
                for ($x = 0; $x < count($inital_match_rate_percentage); $x = $x + 1) {
                    $check_value_1 = $inital_match_rate_percentage[$x];
                    if ($ref_value == $check_value_1) {
                        $insert_value = $initial_matching_videos[$x];
                        array_push($final_display_items, $insert_value);
                    }
                }
            }
        }
        // display the results in format according to mode
        if ($full_search_mode == true) {
            foreach ($final_display_items as $display_item) {
                // get video info
                $video_display_info = $database_access_object->prepared_statment_select_on_one_record("videos", "video_id", $display_item, "s");
                
            }
        }
    }
}
catch (Exception $e) {
    header("Location: ./index.php");
}
?>
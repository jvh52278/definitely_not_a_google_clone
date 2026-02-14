<?php 
$full_search_mode = false; // if true, search for keywords in title, description and uploader name
$display_all = false; // if true, display all results in page view - 25 results per page, with a page counter and navigation bar at the bottom
$display_3_relevant_short = false; // if true, display 3 full search results, only the title and thumbnail 
$admin_moderation_view = false; // if true, display the following items: title, description, thumbnail, uploader name, uploader date -> and results link to moderation page instead of the video player
$custom_results = $override_default_start_values; // if true (set before including this module), start the search/display process with a custom query (retrieved before this module)
/*
put this on every page that uses this module, change as needed
$display_mode_input = "full";
$override_default_start_values = false;
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
    if ($custom_results == true) {
        $results_to_display = $custom_start_results;
    }
    if ($full_search_mode == true || $display_3_relevant_short == true) {
        // matching arrays -> video_id to search result match percentage
        $initial_matching_videos = array();
        $inital_match_rate_percentage = array();
        //
        $individual_search_terms = $seperated_search_terms;
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
            $title_sub_match = $title_matches_found/$values_to_match;
            // description match -> 30%
            $description_sub_match = 0;
            $description_matches_found = 0;
            for ($x = 0; $x < count($individual_search_terms); $x = $x + 1) {
                $current_value = $individual_search_terms[$x];
                if (check_if_string_contains_substring($t_description, $current_value)) {
                    $description_matches_found = $description_matches_found + 1;
                }
            }
            $description_sub_match = $description_matches_found/$values_to_match;
            // uploader name match -> 20%
            $uploader_sub_match = 0;
            $uploader_matches_found = 0;
            for ($x = 0; $x < count($individual_search_terms); $x = $x + 1) {
                $current_value = $individual_search_terms[$x];
                if (check_if_string_contains_substring($t_uploader_username,$current_value)) {
                    $uploader_matches_found = $uploader_matches_found + 1;
                }
            }
            $uploader_sub_match = $uploader_matches_found/$values_to_match;
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
        //- sort from largest to smallest and remove duplicates
        //- loop through the copy, for each value, loop through the first pass of the search and copy any videos that match the relevancy score to a new array
    }
}
catch (Exception $e) {
    header("Location: ./index.php");
}
?>
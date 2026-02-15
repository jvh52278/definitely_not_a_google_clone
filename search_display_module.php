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
        $results_displayed_running_count = 0;
        $number_of_pages = attempt_division(count($final_display_items), 25);
        $number_of_pages_practical_value = -5; // number_of_pages rounded up to the nearest whole number -> if sending back the last page and there are 2 or more pages, send this number back in the form
        $results_per_page = 25;
        $last_page_displayed = $last_page;
        $display_start_point = 0; // start displaying results on and at this count
        if ($number_of_pages > 1 && $last_page_displayed > 1) {
            $previous_page = $last_page_displayed - 1;
            $display_start_point = $previous_page*$results_per_page;
        }
        if ($last_page_displayed > $number_of_pages_practical_value || $last_page_displayed < 1 || $number_of_pages <= 1 || empty($last_page_displayed)) {
            $display_start_point = 0;
        }
        // if the last page value is not a number, make it 0
        // if the last page value is not a whole number, make it 0
        //
        $display_count_limit_at_last_page = $last_page_displayed*$results_per_page; // stop displaying results if this display count is reached, only if more than 1 page exists -> show this result, but not the next one
        //- calculation of a partial page
        /*
        example:
        2.3 pages = 3 pages
        10 results per page
        start at page 2 -> start on 20 results // already calculated

        if last_page is greater than 1 && number_of_pages is 2 or greater && last_page is (number_of_pages - 1) 
        end point = index_of_last_result_record
        */
        if ($full_search_mode == true) {
            foreach ($final_display_items as $display_item) {
                // get video info
                $video_display_info = $database_access_object->prepared_statment_select_on_one_record("videos", "video_id", $display_item, "s");
                //
                $uploader_id = $video_display_info[0]["uploader"];
                $uploader_info = $database_access_object->prepared_statment_select_on_one_record("users", "user_id", $uploader_id, "s");
                //
                $link_video_id = $display_item;
                $display_title = $video_display_info[0]["title"];
                $display_description = $video_display_info[0]["description"];
                $display_thumbnail = $video_display_info[0]["path_to_thumbnail"];
                $display_uploader_username = $uploader_info[0]["user_name"];
                $display_video_link = "";
                if ($admin_moderation_view == true) {
                    $display_video_link = "";
                } else {
                    $display_video_link = "video.php?video_id=$link_video_id"; // send input name: video_id
                }

                $display_container_standard = "<a style='color=white;' href='$display_video_link'>$display_title</a><br>"; //the html to display for each item without the delete option -> each item is a self contained div with class="full_display"
                $display_container_with_delete = ""; // self contained div with delete option
                $page_navigation_bar = ""; // to navigate between pages, only show if more than 1 page exists
                if ($show_delete_option == true) {
                    echo $display_container_with_delete;
                } else {
                    echo $display_container_standard;
                }
                if ($number_of_pages > 0) {
                    echo $page_navigation_bar;
                }
            }
        }
    }
}
catch (Exception $e) {
    header("Location: ./index.php");
}
?>
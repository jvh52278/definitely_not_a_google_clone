<?php 
$full_search_mode = false; // if true, search for keywords in title, description and uploader name
$display_all = false; // if true, display all results in page view - 25 results per page, with a page counter and navigation bar at the bottom
$display_3_relevant_short = false; // if true, display 3 full search results, only the title and thumbnail 
$admin_moderation_view = $admin_moderation_mode_active; // if true, display the following items: title, description, thumbnail, uploader name, uploader date -> and results link to moderation page instead of the video player
$show_delete_option = $delete_option_active; // if true, show video delete button
$custom_results = $override_default_start_values; // if true (set before including this module), start the search/display process with a custom query (retrieved before this module)
/*
put this on every page that uses this module, change as needed
//$seperated_search_terms = return_seperated_alnum_chars($search_term_input_value);
$display_mode_input = "full"; // "full", "all" or "short"
$override_default_start_values = false;
$delete_option_active = false;
$delete_button_user_in_moderation_view = false;
$admin_moderation_mode_active = false;
$custom_start_results = array(); // use if $override_default_start_values is true
$self_redirect_link = ""; // the relative file path of the page
//
$last_page = $_GET["last_page_displayed"];// retrieve this form input for pagination view
if (empty($last_page)) {
    $last_page = 0;
}
$search_input = check_and_replace_if_variable_is_empty(trim_spaces_from_string($_GET["search_terms"])); // retrieve this input for pagination view
$seperated_search_terms = return_seperated_alnum_chars($search_input);
// use this only if displaying in short mode
$ignore_this = ""; // the id of the video to not display -> to avoid showing the currently displayed video
*/

$link_back_to_user_manage_page = false;

if (!empty($delete_button_user_in_moderation_view) && $delete_button_user_in_moderation_view == true) {
    $link_back_to_user_manage_page = true;
}


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
    if ($full_search_mode == true || $display_3_relevant_short == true || $display_all == true) {
        // matching arrays -> video_id to search result match percentage
        $initial_matching_videos = array();
        $inital_match_rate_percentage = array();
        //
        $individual_search_terms = array();
        if ($full_search_mode == true || $display_3_relevant_short == true) {
            $individual_search_terms = $seperated_search_terms;
        }
        if (count($individual_search_terms) == 0) {
            $search_terms_are_blank = true;
        }
        //var_dump(count($individual_search_terms));
        //print_debug_test_value($search_terms_are_blank, "white");
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
        if ($search_terms_are_blank == true || $display_all == true) {
            //print_debug_test_value("blank search term path selected", "white");
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
        $results_per_page = 15;
        $number_of_pages = attempt_division(count($final_display_items), $results_per_page);
        $number_of_pages_practical_value = ceil($number_of_pages); // number_of_pages rounded up to the nearest whole number -> if sending back the last page and there are 2 or more pages, send this number back in the form
        //
        //
        $last_page_displayed = $last_page; // this is the practical number
        //print_debug_test_value(is_int($last_page_displayed), "white");
        if (!is_int($last_page_displayed)) {
            if (is_numeric($last_page_displayed)) {
                if (!check_if_number_string_is_int($last_page_displayed)) {
                    $last_page_displayed = 1;
                }
            }
        } else {
            $last_page_displayed = 1;
        }
        $display_start_point = 0; // start displaying results on and at this count
        if ($number_of_pages > 1 && $last_page_displayed > 1) {
            $previous_page = $last_page_displayed - 1;
            $display_start_point = ($previous_page*$results_per_page) + 1;
        }
        if ($last_page_displayed > $number_of_pages_practical_value || $last_page_displayed < 1 || $number_of_pages <= 1 || empty($last_page_displayed)) {
            $display_start_point = 1;
        }
        // if the last page value is not a number, make it 1
        if (!is_numeric($last_page_displayed)) {
            $last_page_displayed = 1;
        }
        // if the last page value is not a whole number, make it 1
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
        if ($last_page_displayed == 0) {
            $display_start_point = 1;
        }
        if ($number_of_pages <= 1) {
            $display_count_limit_at_last_page = count($final_display_items);
        }
        $next_button_value = "";
        $back_button_value = "";
        $last_page_standalone_value = "";
        // if there is more than 1 page
        if ($number_of_pages > 1) {
            // if starting from the first display entry
            if ($display_start_point == 1) {
                $last_page_displayed = 1;
                $display_count_limit_at_last_page = $last_page_displayed*$results_per_page;
            }
            // if the last page displayed is 1 -> the first page
            if ($last_page_displayed == 1) {
                $display_start_point = 1;
                $display_count_limit_at_last_page = $last_page_displayed*$results_per_page;
            }
            // adjust the back button code
            if ($last_page_displayed == 1) {
                $back_button_value = "<p class='center_link_blank'>back</p>";
            }
            if ($last_page_displayed > 1) {
                $last_page_number = $last_page_displayed - 1;
                $p_insert_value = urlencode(strval($last_page_number));
                $last_page_standalone_value = urlencode(strval($last_page_displayed));
                $p_search_value = urlencode($search_input);
                $back_link = $self_redirect_link."?search_terms=".$p_search_value."&last_page_displayed=".$p_insert_value;
                $back_button_value = "<p class='center_link'><a href=$back_link>back</a></p>";
            }
            // adjust the next button code
            $next_page_number = $last_page_displayed + 1;
            if ($next_page_number > $number_of_pages_practical_value) {
                $next_button_value = "<p class='center_link_blank'>next</p>";
            } else {
                $p_insert_value = urlencode(strval($next_page_number));
                $p_search_value = urlencode($search_input);
                $next_link = $self_redirect_link."?search_terms=".$p_search_value."&last_page_displayed=".$p_insert_value;
                $next_button_value = "<p class='center_link'><a href=$next_link>next</a></p>";
            }
        }
        $page_navigation_bar = 
        "
        <div class='page_navigation_section'>
            <div class='nav_back_section'>
                $back_button_value
            </div>
            <div class='current_page_index'>
                <p class='center_display'>$last_page_displayed/$number_of_pages_practical_value</p>
            </div>
            <div class='nav_next_section'>
                $next_button_value
            </div>
        </div>
        "; // to navigate between pages, only show if more than 1 page exists
        if ($full_search_mode == true || $display_all == true) {
            if (count($final_display_items) > 0) { 
                foreach ($final_display_items as $display_item) {
                    $results_displayed_running_count = $results_displayed_running_count + 1;
                    if ($results_displayed_running_count >= $display_start_point && $results_displayed_running_count <= $display_count_limit_at_last_page) {
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
                        $display_upload_date = $video_display_info[0]["upload_date"];
                        $human_readable_date = date('m-d-Y H:i:s T', $display_upload_date);
                        $display_video_link = "";
                        if ($admin_moderation_view == true) {
                            $display_video_link = "review_user_upload.php?video_id=$link_video_id&last_page_displayed=$last_page_standalone_value";
                        } else {
                            $display_video_link = "video.php?video_id=$link_video_id"; // send input name: video_id
                        }

                        $display_container_standard = 
                        "<div class='full_display'>
                            <div class='full_display_section_image_small'>
                                <a href='$display_video_link'><img src='$display_thumbnail' alt='$display_title'></a>
                            </div>
                            <div class='full_display_section'>
                                <p><a class='list_view_link' href='$display_video_link'>$display_title</a></p>
                            </div>
                            <div class='full_display_section_word_wrapped'>
                                <p>Uploaded by $display_uploader_username on $human_readable_date</p>
                            </div>
                        </div>"; //the html to display for each item without the delete option -> each item is a self contained div with class="full_display"
                        $display_container_with_delete =                         "<div class='full_display'>
                            <div class='full_display_section_image_small'>
                                <a href='$display_video_link'><img src='$display_thumbnail' alt='$display_title'></a>
                            </div>
                            <div class='full_display_section'>
                                <p><a class='list_view_link' href='$display_video_link'>$display_title</a></p>
                            </div>
                            <div class='full_display_section_word_wrapped'>
                                <p>Uploaded by $display_uploader_username on $human_readable_date</p>
                                <a class='line_display_link' href='delete_processing.php?v=$link_video_id&last_page_displayed=$last_page_standalone_value&rd=m'>|-- Delete --|</a>
                            </div>
                        </div>";
                        $display_container_moderation_mode =                         "<div class='full_display'>
                            <div class='full_display_section_image_small'>
                                <a href='$display_video_link'><img src='$display_thumbnail' alt='$display_title'></a>
                            </div>
                            <div class='full_display_section'>
                                <p><a class='list_view_link' href='$display_video_link'>$display_title</a></p>
                            </div>
                            <div class='full_display_section_word_wrapped'>
                                <p>Uploaded by $display_uploader_username on $human_readable_date</p>
                                <a class='line_display_link' href='delete_processing.php?v=$link_video_id&last_page_displayed=$last_page_standalone_value&rd=a'>|-- Delete --|</a>
                            </div>
                        </div>";
                        if ($show_delete_option == true && $admin_moderation_mode_active != true) {
                            echo $display_container_with_delete;
                        } else {
                            if ($admin_moderation_mode_active == true) {
                                echo $display_container_moderation_mode;
                            } else {
                                echo $display_container_standard;
                            }
                        }
                    }
                }
            } else {
                echo "<h2 class='empty_message'>No results found</h2>";
            }
            if ($number_of_pages > 1) {
                echo $page_navigation_bar;
            }
        }
        if ($display_3_relevant_short == true) {
            $display_count = 0;
            if (count($final_display_items) > 0) { 
                echo "<div class='short_display'>";
                foreach ($final_display_items as $display_item) {
                    if ($display_item != $ignore_this) {
                        $display_count = $display_count + 1;
                    }
                    if ($display_count > 0 && $display_count <= 3 && $display_item != $ignore_this) {
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
                        $display_upload_date = $video_display_info[0]["upload_date"];
                        $human_readable_date = date('m-d-Y H:i:s T', $display_upload_date);
                        $display_video_link = "video.php?video_id=$link_video_id";
                        //
                        $link_container = 
                        "
                        <div class='short_display_section'>
                            <a href='$display_video_link'>
                                <p class='sd_small_text'>$display_title</p>
                                <img class='sd_image' src='$display_thumbnail' alt='$display_title'>
                            </a>
                        </div>
                        ";
                        echo $link_container;
                    }
                }
                // fill blank spaces in recommendation section
                $missing_display_elements = -5;
                if ($display_count >= 1 && $display_count < 3) {
                    $missing_display_elements = 3 - $display_count;
                }
                if ($missing_display_elements > -5) {
                    for ($x = 1; $x <= $missing_display_elements; $x = $x + 1) {
                        $placeholder_image = "images/black_space.png";
                        $placeholder_section =
                        "
                        <div class='short_display_placeholder'>
                            <p style='color:var(--primary_page_background);' class='sd_small_text_hidden'>blank</p>
                            <img class='sd_image_hidden' src='$placeholder_image' alt='blank'>
                        </div>
                        ";
                        echo $placeholder_section;
                    }
                }
                echo "</div>";
            }
        }
    }
}
catch (Exception $e) {
    header("Location: ./index.php");
}
?>
<?php
function trim_spaces_from_string ($variable_to_trim_any) {
    $return_value = trim($variable_to_trim_any);
    return $return_value;
}

function copy_text ($start_index_int, $end_index_int, $text_source_string) { # copy all text from (including) one index to (including) another
    $return_string = "";
    for ($x = $start_index_int; $x <= $end_index_int; $x = $x + 1) {
        $return_string = $return_string.$text_source_string[$x];
    }
    return $return_string;
}

function check_if_string_contains_substring ($string_to_check, $substring_to_look_for) {
    $substring_found = false;
    $number_of_chars_in_substring = strlen($substring_to_look_for);
    try {
        for ($x = 0; $x < strlen($string_to_check); $x = $x + 1) {
            # try to find a match if one hasn't already been found
            if (($substring_found == false)  && (strtolower($string_to_check[$x]) == strtolower($substring_to_look_for[0]))) {
                $check_string = copy_text($x, $x + $number_of_chars_in_substring - 1, $string_to_check);
                # if the check string is a match, return true
                if (strtolower($check_string) == strtolower($substring_to_look_for)) {
                    $substring_found = true;
                    //echo $check_string;
                    return $substring_found;
                }
            }
        }
    }
    catch (Exception $e) {
        $substring_found = false;
        return $substring_found;
    }
}
?>
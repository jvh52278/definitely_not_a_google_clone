<?php
function trim_spaces_from_string ($variable_to_trim_any) {
    $return_value = trim($variable_to_trim_any);
    return $return_value;
}

function check_and_replace_if_variable_is_empty ($variable_to_check_any) {
    $return_value = $variable_to_check_any;
    # if the value is empty, return an empty string
    if (empty($variable_to_check_any)) {
        $return_value = "";
    }
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

function print_debug_test_value ($value_to_print, $string_text_white_or_black) {
    $text_color = "";
    if ($string_text_white_or_black == "white") {
        $text_color = "white";
    } elseif ($string_text_white_or_black == "black") {
        $text_color = "black";
    } else {
        $text_color = "yellow";
    }
    echo "<p style='color: $text_color;'>#### test values ###</p>";
    echo "<p style='color: $text_color;'>$value_to_print</p>";
}

function replace_spaces($string_input_string) {
    $return_value = "";
    for ($x = 0; $x < strlen($string_input_string); $x = $x + 1) {
        $current_char = $string_input_string[$x];
        if (!is_numeric($current_char) && !ctype_alpha($current_char)) {
            $return_value = $return_value."_";
        } else {
            $return_value = $return_value.$current_char;
        }
    }
    return $return_value;
}

function return_seperated_alnum_chars ($string_input_string) {
    $return_array = array();
    $temp_string = "";
    for ($x = 0; $x < strlen($string_input_string); $x = $x + 1) {
        $current_char = $string_input_string[$x];
        if (ctype_alnum($current_char) || $current_char == "'") {
            $temp_string = $temp_string.$current_char;
        }
        if ((!ctype_alnum($current_char) && $current_char != "'") || ($x == strlen($string_input_string) - 1)) {
            if (!empty($temp_string)) {
                array_push($return_array,$temp_string);
                $temp_string = "";
            }
        }
    }
    return $return_array;
}
?>
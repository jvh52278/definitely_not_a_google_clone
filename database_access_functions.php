<?php
class database_access_object {
    // set private variables for database access information
    private $database_user;
    private $database_user_password;
    private $database_name;
    private $sql_server_name;
    // ### set functions ###
    // set database access variables
    function set_database_access_variables($database_user_input, $database_user_password_input, $database_name_input, $sql_server_input) {
        // set the database access variables
        $this->database_user = $database_user_input; // set the database user
        $this->database_user_password = $database_user_password_input; // set the database use password
        $this->database_name = $database_name_input; // set the database name
        $this->sql_server_name = $sql_server_input; // set the sql server name
    }
    // retrieve all records from a table - prepared statement not needed -> no user form inputs are used
    function retrieve_all_records_from_table ($table_to_access_string) {
        $con = new mysqli($this->sql_server_name,$this->database_user, $this->database_user_password, $this->database_name);
        // error handling -- if the connection fails
        if ($con->connect_errno) {
            // do stuff if the connection fails
        }
        $sql_query = "SELECT * FROM $table_to_access_string";
        $query_object = $con->query($sql_query); // run the sql query -- put the sql code here
        if ($query_object) { // put in this part if the sql query returns results. If not, this part is not required
            $query_results = mysqli_fetch_all($query_object, MYSQLI_ASSOC); // NOTE: mysqli_fetch_all retreives all results from the sql query
            // ### return results to an array ###
            return $query_results;
            // ####################################
        }
    }
    // return all records based on one identifying record - prepared statement needed due to user input
    function prepared_statment_select_on_one_record ($table_to_access_string, $column_containing_identifying_record_1_string, $identifying_record_1_any, $identifying_record_1_data_type_string_idsb) {
        // ### run the sql query ###
        $database_connection = new mysqli($this->sql_server_name, $this->database_user, $this->database_user_password, $this->database_name);
        // step 1A: prepare the query
            // create the query, but put a ? where a user / dynamic input would be
        $sql_query = "SELECT * FROM $table_to_access_string WHERE $column_containing_identifying_record_1_string = ?";
        $prepared_sql_query = $database_connection->prepare($sql_query);
        // step 1B: bind the user / dynamic input variables to fixed data types
            // bind_param('|input_1_datatype|input_2_datatype|....', $input_1, $input_2, .....)
            // NOTE: the inputs go in order that they would appear in the query, left to right, where the ? are
        $prepared_sql_query->bind_param($identifying_record_1_data_type_string_idsb, $identifying_record_1_any);
        // step 2A: execute the prepared query
        $prepared_sql_query->execute(); // NOTE: this does not return the result, if any exists
        // step 2B: retrieve the results of the query and put them into an array
        $query_results = $prepared_sql_query->get_result()->fetch_all(MYSQLI_ASSOC);
        return $query_results;
        // close the prepared query
        $prepared_sql_query->close();
    }
    // return all records based on two identifying records
    function prepared_statment_select_on_two_records ($table_to_access_string, $column_containing_identifying_record_1_string, $identifying_record_1_any, $identifying_record_1_data_type_string_idsb, $column_containing_identifying_record_2_string, $identifying_record_2_any, $identifying_record_2_data_type_string_idsb) {
        // ### run the sql query ###
        $database_connection = new mysqli($this->sql_server_name, $this->database_user, $this->database_user_password, $this->database_name);
        // step 1A: prepare the query
            // create the query, but put a ? where a user / dynamic input would be
        $sql_query = "SELECT * FROM $table_to_access_string WHERE $column_containing_identifying_record_1_string = ? AND $column_containing_identifying_record_2_string = ?";
        $prepared_sql_query = $database_connection->prepare($sql_query);
        // step 1B: bind the user / dynamic input variables to fixed data types
            // bind_param('|input_1_datatype|input_2_datatype|....', $input_1, $input_2, .....)
            // NOTE: the inputs go in order that they would appear in the query, left to right, where the ? are
        $data_type_string = $identifying_record_1_data_type_string_idsb.$identifying_record_2_data_type_string_idsb;
        $prepared_sql_query->bind_param($data_type_string, $identifying_record_1_any, $identifying_record_2_any);
        // step 2A: execute the prepared query
        $prepared_sql_query->execute(); // NOTE: this does not return the result, if any exists
        // step 2B: retrieve the results of the query and put them into an array
        $query_results = $prepared_sql_query->get_result()->fetch_all(MYSQLI_ASSOC);
        return $query_results;
        // close the prepared query
        $prepared_sql_query->close();
    }
    // return all records based on three identifying records
    function prepared_statment_select_on_three_records ($table_to_access_string, $column_containing_identifying_record_1_string, $identifying_record_1_any, $identifying_record_1_data_type_string_idsb, $column_containing_identifying_record_2_string, $identifying_record_2_any, $identifying_record_2_data_type_string_idsb, $column_containing_identifying_record_3_string, $identifying_record_3_any, $identifying_record_3_data_type_string_idsb) {
        // ### run the sql query ###
        $database_connection = new mysqli($this->sql_server_name, $this->database_user, $this->database_user_password, $this->database_name);
        // step 1A: prepare the query
            // create the query, but put a ? where a user / dynamic input would be
        $sql_query = "SELECT * FROM $table_to_access_string WHERE $column_containing_identifying_record_1_string = ? AND $column_containing_identifying_record_2_string = ? AND $column_containing_identifying_record_3_string = ?";
        $prepared_sql_query = $database_connection->prepare($sql_query);
        // step 1B: bind the user / dynamic input variables to fixed data types
            // bind_param('|input_1_datatype|input_2_datatype|....', $input_1, $input_2, .....)
            // NOTE: the inputs go in order that they would appear in the query, left to right, where the ? are
        $data_type_string = $identifying_record_1_data_type_string_idsb.$identifying_record_2_data_type_string_idsb.$identifying_record_3_data_type_string_idsb;
        $prepared_sql_query->bind_param($data_type_string, $identifying_record_1_any, $identifying_record_2_any, $identifying_record_3_any);
        // step 2A: execute the prepared query
        $prepared_sql_query->execute(); // NOTE: this does not return the result, if any exists
        // step 2B: retrieve the results of the query and put them into an array
        $query_results = $prepared_sql_query->get_result()->fetch_all(MYSQLI_ASSOC);
        return $query_results;
        // close the prepared query
        $prepared_sql_query->close();
    }
    // update one record based on one identifying record
    function prepared_statment_update_on_one_record ($table_to_update_string, $column_containing_identifying_record_1_string, $identifying_record_1_any, $identifying_record_1_data_type_string_idsb, $column_to_update_string, $updated_value, $updated_value_data_type_idsb) {
        // ### run the sql query ###
        $database_connection = new mysqli($this->sql_server_name, $this->database_user, $this->database_user_password, $this->database_name);
        // step 1A: prepare the query
            // create the query, but put a ? where a user / dynamic input would be
        $sql_query = "UPDATE $table_to_update_string SET $column_to_update_string = ? WHERE $column_containing_identifying_record_1_string = ?";
        $prepared_sql_query = $database_connection->prepare($sql_query);
        // step 1B: bind the user / dynamic input variables to fixed data types
            // bind_param('|input_1_datatype|input_2_datatype|....', $input_1, $input_2, .....)
            // NOTE: the inputs go in order that they would appear in the query, left to right, where the ? are
        $prepared_sql_query->bind_param($updated_value_data_type_idsb.$identifying_record_1_data_type_string_idsb, $updated_value,$identifying_record_1_any);
        // step 2A: execute the prepared query
        $prepared_sql_query->execute(); // NOTE: this does not return the result, if any exists
        // close the prepared query
        $prepared_sql_query->close();
    }
    // update one record based on two identifying records
    function prepared_statment_update_on_two_records ($table_to_update_string, $column_containing_identifying_record_1_string, $identifying_record_1_any, $identifying_record_1_data_type_string_idsb, $column_containing_identifying_record_2_string, $identifying_record_2_any, $identifying_record_2_data_type_string_idsb, $column_to_update_string, $updated_value, $updated_value_data_type_idsb) {
        // ### run the sql query ###
        $database_connection = new mysqli($this->sql_server_name, $this->database_user, $this->database_user_password, $this->database_name);
        // step 1A: prepare the query
            // create the query, but put a ? where a user / dynamic input would be
        $sql_query = "UPDATE $table_to_update_string SET $column_to_update_string = ? WHERE $column_containing_identifying_record_1_string = ? AND $column_containing_identifying_record_2_string = ?";
        $prepared_sql_query = $database_connection->prepare($sql_query);
        // step 1B: bind the user / dynamic input variables to fixed data types
            // bind_param('|input_1_datatype|input_2_datatype|....', $input_1, $input_2, .....)
            // NOTE: the inputs go in order that they would appear in the query, left to right, where the ? are
        $input_datatypes = $updated_value_data_type_idsb.$identifying_record_1_data_type_string_idsb.$identifying_record_2_data_type_string_idsb;
        $prepared_sql_query->bind_param($input_datatypes, $updated_value, $identifying_record_1_any, $identifying_record_2_any);
        // step 2A: execute the prepared query
        $prepared_sql_query->execute(); // NOTE: this does not return the result, if any exists
        // close the prepared query
        $prepared_sql_query->close();
    }
    // update one record base on three identifying records
    function prepared_statment_update_on_three_records ($table_to_update_string, $column_containing_identifying_record_1_string, $identifying_record_1_any, $identifying_record_1_data_type_string_idsb, $column_containing_identifying_record_2_string, $identifying_record_2_any, $identifying_record_2_data_type_string_idsb, $column_containing_identifying_record_3_string, $identifying_record_3_any, $identifying_record_3_data_type_string_idsb, $column_to_update_string, $updated_value, $updated_value_data_type_idsb) {
        // ### run the sql query ###
        $database_connection = new mysqli($this->sql_server_name, $this->database_user, $this->database_user_password, $this->database_name);
        // step 1A: prepare the query
            // create the query, but put a ? where a user / dynamic input would be
        $sql_query = "UPDATE $table_to_update_string SET $column_to_update_string = ? WHERE $column_containing_identifying_record_1_string = ? AND $column_containing_identifying_record_2_string = ? AND $column_containing_identifying_record_3_string = ?";
        $prepared_sql_query = $database_connection->prepare($sql_query);
        // step 1B: bind the user / dynamic input variables to fixed data types
            // bind_param('|input_1_datatype|input_2_datatype|....', $input_1, $input_2, .....)
            // NOTE: the inputs go in order that they would appear in the query, left to right, where the ? are
        $input_datatypes = $updated_value_data_type_idsb.$identifying_record_1_data_type_string_idsb.$identifying_record_2_data_type_string_idsb.$identifying_record_3_data_type_string_idsb;
        $prepared_sql_query->bind_param($input_datatypes, $updated_value, $identifying_record_1_any, $identifying_record_2_any, $identifying_record_3_any);
        // step 2A: execute the prepared query
        $prepared_sql_query->execute(); // NOTE: this does not return the result, if any exists
        // close the prepared query
        $prepared_sql_query->close();
    }
    // Delete records based on one identifying record
    function prepared_statment_delete_on_one_record ($table_to_access_string, $column_containing_identifying_record_1_string, $identifying_record_1_any, $identifying_record_1_data_type_string_idsb) {
        // ### run the sql query ###
        $database_connection = new mysqli($this->sql_server_name, $this->database_user, $this->database_user_password, $this->database_name);
        // step 1A: prepare the query
            // create the query, but put a ? where a user / dynamic input would be
        $sql_query = "DELETE FROM $table_to_access_string WHERE $column_containing_identifying_record_1_string = ?";
        $prepared_sql_query = $database_connection->prepare($sql_query);
        // step 1B: bind the user / dynamic input variables to fixed data types
            // bind_param('|input_1_datatype|input_2_datatype|....', $input_1, $input_2, .....)
            // NOTE: the inputs go in order that they would appear in the query, left to right, where the ? are
        $prepared_sql_query->bind_param($identifying_record_1_data_type_string_idsb, $identifying_record_1_any);
        // step 2A: execute the prepared query
        $prepared_sql_query->execute(); // NOTE: this does not return the result, if any exists
        // close the prepared query
        $prepared_sql_query->close();
    }
    // delete records based on two identifying records
    function prepared_statment_delete_on_two_records ($table_to_access_string, $column_containing_identifying_record_1_string, $identifying_record_1_any, $identifying_record_1_data_type_string_idsb, $column_containing_identifying_record_2_string, $identifying_record_2_any, $identifying_record_2_data_type_string_idsb) {
        // ### run the sql query ###
        $database_connection = new mysqli($this->sql_server_name, $this->database_user, $this->database_user_password, $this->database_name);
        // step 1A: prepare the query
            // create the query, but put a ? where a user / dynamic input would be
        $sql_query = "DELETE FROM $table_to_access_string WHERE $column_containing_identifying_record_1_string = ? AND $column_containing_identifying_record_2_string = ?";
        $prepared_sql_query = $database_connection->prepare($sql_query);
        // step 1B: bind the user / dynamic input variables to fixed data types
            // bind_param('|input_1_datatype|input_2_datatype|....', $input_1, $input_2, .....)
            // NOTE: the inputs go in order that they would appear in the query, left to right, where the ? are
        $data_type_string = $identifying_record_1_data_type_string_idsb.$identifying_record_2_data_type_string_idsb;
        $prepared_sql_query->bind_param($data_type_string, $identifying_record_1_any, $identifying_record_2_any);
        // step 2A: execute the prepared query
        $prepared_sql_query->execute(); // NOTE: this does not return the result, if any exists
        // close the prepared query
        $prepared_sql_query->close();
    }
    // delete records based on 3 identifying records
    function prepared_statment_delete_on_three_records ($table_to_access_string, $column_containing_identifying_record_1_string, $identifying_record_1_any, $identifying_record_1_data_type_string_idsb, $column_containing_identifying_record_2_string, $identifying_record_2_any, $identifying_record_2_data_type_string_idsb, $column_containing_identifying_record_3_string, $identifying_record_3_any, $identifying_record_3_data_type_string_idsb) {
        // ### run the sql query ###
        $database_connection = new mysqli($this->sql_server_name, $this->database_user, $this->database_user_password, $this->database_name);
        // step 1A: prepare the query
            // create the query, but put a ? where a user / dynamic input would be
        $sql_query = "DELETE FROM $table_to_access_string WHERE $column_containing_identifying_record_1_string = ? AND $column_containing_identifying_record_2_string = ? AND $column_containing_identifying_record_3_string = ?";
        $prepared_sql_query = $database_connection->prepare($sql_query);
        // step 1B: bind the user / dynamic input variables to fixed data types
            // bind_param('|input_1_datatype|input_2_datatype|....', $input_1, $input_2, .....)
            // NOTE: the inputs go in order that they would appear in the query, left to right, where the ? are
        $data_type_string = $identifying_record_1_data_type_string_idsb.$identifying_record_2_data_type_string_idsb.$identifying_record_3_data_type_string_idsb;
        $prepared_sql_query->bind_param($data_type_string, $identifying_record_1_any, $identifying_record_2_any, $identifying_record_3_any);
        // step 2A: execute the prepared query
        $prepared_sql_query->execute(); // NOTE: this does not return the result, if any exists
        // close the prepared query
        $prepared_sql_query->close();
    }
    // create a random text string
    function create_random_string () {
        // get the time and date in unix time
        //$current_date_time = date('Y-m-d H:i:s');
        //$current_date_time = strtotime($current_date_time);
        $current_date_time = round(microtime(true),3)*1000;
        // create a random 10 char string
        $possible_chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!_-+=";
        $random_string = "";
        for ($x = 0; $x <= 10; $x = $x + 1) {
            $random_index = rand(0,strlen($possible_chars) - 1);
            $random_string = $random_string.$possible_chars[$random_index];
        }
        // append the random string to (after) the time stamp
        $random_string_complete = strval($current_date_time).$random_string;
        return $random_string_complete;
    }
    
}
// ############# create the instance of class here - so that database access information can easily be changed for other deployments #########
// create an instance of the class
$database_access_object = new database_access_object();
// ## test set database access info - change this to reflect your own database user, database name and server name
$database_access_object->set_database_access_variables("sqladmin","sqladmin","youtube_clone","localhost");
?>
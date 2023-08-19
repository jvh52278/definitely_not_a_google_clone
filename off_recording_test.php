<?php
 // non prepared statment
 $results_from_non_prepared_statment = array();

 $database_user = "database_user";
 $database_user_password = "database_user_password";
 $database_name = "database_name";
 $sql_server = "name_of_sql_server"; // the name of the sql database on the server hosting system is "localhost"
 $con = new mysqli($sql_server,$database_user, $database_user_password, $database_name);
 // error handling -- if the connection fails
 if ($con->connect_errno) {
     // do stuff if the connection fails
 }
 $sql_query = "sql_query";
 $query_object = $con->query($sql_query); // run the sql query -- put the sql code here
 if ($query_object) { // put in this part if the sql query returns results. If not, this part is not required
     $query_results = mysqli_fetch_all($query_object, MYSQLI_ASSOC); // NOTE: mysqli_fetch_all retreives all results from the sql query
     // ### return results to an array ###
     $results_from_non_prepared_statment = $query_results;
     // ####################################
 }
/*
 echo "results from non prepared statment, using a for each loop:<br>";
 foreach ($results_from_non_prepared_statment as $record_row) {
     echo "for user id: ".$record_row['user_id'];
     echo "<br>";
     echo "username: ".$record_row["user_name"];
     echo "<br>";
     echo "password: ".$record_row["password"];
     echo "<br>";
 }

 echo "<br>results from non prepared statment, using a standard for loop:<br>";
 for ($x = 0; $x < count($results_from_non_prepared_statment); $x = $x + 1) {
     echo "for user id: ".$results_from_non_prepared_statment[$x]['user_id'];
     echo "<br>";
     echo "username: ".$results_from_non_prepared_statment[$x]["user_name"];
     echo "<br>";
     echo "password: ".$results_from_non_prepared_statment[$x]["password"];
     echo "<br>";
 }
 */

 // prepared statement
 echo "<br>results from prepared statement:<br>";
 // ### prepare access and customization variables ###
 // database access variables
 $database_user = "database_user";
 $database_user_password = "database_user_password";
 $database_name = "database_name";
 $sql_server = "name_of_sql_server"; // the name of the sql database on the server hosting system is "localhost"
 // query customization variables -- to define fixed parts of the query
 $table_to_access = "users";
 $identifying_column = "user_id";
 // query customization variables -- for the dynamic / user input 
     // this is the ?
 $identifying_record = 1;
 $identifying_record_data_type = "i";
     // identifying_record_datatype: i -> int
     // identifying_record_datatype: d -> float
     // identifying_record_datatype: s -> string
     // identifying_record_datatype: b -> blob, sent in packets
 // ### run the sql query ###
 $database_connection = new mysqli($sql_server_name, $database_user, $database_user_password, $database_name);
 // step 1A: prepare the query
     // create the query, but put a ? where a user / dynamic input would be
 $sql_query = "SELECT * FROM $table_to_access WHERE $identifying_column = ?";
 $prepared_sql_query = $database_connection->prepare($sql_query);
 // step 1B: bind the user / dynamic input variables to fixed data types
     // bind_param('|input_1_datatype|input_2_datatype|....', $input_1, $input_2, .....)
     // NOTE: the inputs go in order that they would appear in the query, left to right, where the ? are
     // datatype: i -> int
     // datatype: d -> float
     // datatype: s -> string
     // datatype: b -> blob, sent in packets
 $prepared_sql_query->bind_param($identifying_record_data_type, $identifying_record);
 // step 2A: execute the prepared query
 $prepared_sql_query->execute(); // NOTE: this does not return the result, if any exists
 // step 2B: retrieve the results of the query and put them into an array
 $query_results = $prepared_sql_query->get_result()->fetch_all(MYSQLI_ASSOC);
 // close the prepared query
 $prepared_sql_query->close();

 /*
 echo "username: ".$query_results[0]["user_name"]."<br>";
 echo "password: ".$query_results[0]["password"]."<br>";
 echo "<br><br>";
 */

 /*
 // testing a class
 class test_class {
     // set private variables
     private $private_variable_1 = 0;
     private $private_variable_2 = 0;
     // set functions
     function set_private_variables($input_1, $input_2) { // set private variables
         $this->private_variable_1 = $input_1;
         $this->private_variable_2 = $input_2;
         // note: to access private variables, $this->private_variable_name
     }
     function return_private_values () {
         $return_value = $this->private_variable_1 * $this->private_variable_2;
         return $return_value;
     }
 }
 // create an instance of a class
 $test_class_instance = new test_class();
 // run functions from the class
 $test_class_instance->set_private_variables(9,6);
 $test_return_value = $test_class_instance->return_private_values();
 // not part of the class example
 echo $test_return_value;
 */
?>
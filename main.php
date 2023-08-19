<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home page</title>
</head>
<body>
    <?php
    // testing prepared statements
    $database_user = "sqladmin";
    $database_user_password = "sqladmin";
    $database_name = "youtube_clone";
    $sql_server = "localhost"; // the name of the sql database on the server hosting system is "localhost"
    // query customization variables -- to define fixed parts of the query
    $table_to_access = "videos";
    $identifying_column_1 = "title";
    $identifying_column_2 = "uploader";
    // query customization variables -- for the dynamic / user input 
        // this is the ?
    $identifying_record_1 = "title 1";
    $identifying_record_2 = "user 1";
    $identifying_record_data_type = "ss";
        // identifying_record_datatype: i -> int
        // identifying_record_datatype: d -> float
        // identifying_record_datatype: s -> string
        // identifying_record_datatype: b -> blob, sent in packets
    // ### run the sql query ###
    $database_connection = new mysqli($sql_server_name, $database_user, $database_user_password, $database_name);
    // step 1A: prepare the query
        // create the query, but put a ? where a user / dynamic input would be
    $sql_query = "SELECT * FROM $table_to_access WHERE $identifying_column_1 = ? AND $identifying_column_2 = ?";
    $prepared_sql_query = $database_connection->prepare($sql_query);
    // step 1B: bind the user / dynamic input variables to fixed data types
        // bind_param('|input_1_datatype|input_2_datatype|....', $input_1, $input_2, .....)
        // NOTE: the inputs go in order that they would appear in the query, left to right, where the ? are
        // datatype: i -> int
        // datatype: d -> float
        // datatype: s -> string
        // datatype: b -> blob, sent in packets
    $prepared_sql_query->bind_param($identifying_record_data_type, $identifying_record_1, $identifying_record_2);
    // step 2A: execute the prepared query
    $prepared_sql_query->execute(); // NOTE: this does not return the result, if any exists
    // step 2B: retrieve the results of the query and put them into an array
    $query_results = $prepared_sql_query->get_result()->fetch_all(MYSQLI_ASSOC);
    // close the prepared query
    $prepared_sql_query->close();

    print_r($query_results);
    ?>
</body>
</html>
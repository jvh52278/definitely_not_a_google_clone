<?php
// ### link the database access functions
include "./database_access_functions.php";

// ### to do later -> validate that the username and password are valid and do not already exist

// ### temporary measure until backend code is completed -> redirect to registration success page
header("Location: ./register_account_success.php");

?>
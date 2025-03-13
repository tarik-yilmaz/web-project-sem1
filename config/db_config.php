<?php
// File: db_config.php
// Purpose: This file contains the configuration and functions to establish 
// a connection to the MySQL database used in the project. 

// Database configuration constants
define('DB_HOST', 'localhost'); // The hostname of the database server
define('DB_NAME', 'fh_webdevlotr'); // The name of the database
define('DB_USER', 'root'); // The username for database access
define('DB_PASSWORD', ''); // The password for database access (leave empty for local setups)

// Function to establish a connection to the database
function getDbConnection() {
    // Create a new MySQLi connection using the defined constants
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Check for connection errors
    if ($connection->connect_error) {
        // If the connection fails, terminate the script and display an error message
        die("Connection failed: " . $connection->connect_error);
    }

    // Return the successful database connection
    return $connection;
}

// Function to establish a connection without selecting a specific database
function getDbConnectionWithoutDatabase() {
    // Create a new MySQLi connection without specifying a database
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);

    // Check for connection errors
    if ($connection->connect_error) {
        // If the connection fails, terminate the script and display an error message
        die("Connection failed: " . $connection->connect_error);
    }

    // Return the successful connection
    return $connection;
}
?>

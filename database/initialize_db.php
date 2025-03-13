<?php
// File: initialize_db.php
// Purpose: This script initializes the database by reading and executing SQL commands from a schema file.

require_once('../config/db_config.php'); // Include the database configuration file

// Specify the schema file containing SQL commands
$sql_file = 'schema.sql';
$sql_content = file_get_contents($sql_file); // Load the content of the schema file

// Check if the schema file was successfully read
if ($sql_content === false) {
    // Terminate the script if the file cannot be read and display an error
    die("Error: Could not read the schema.sql file.");
}

// Establish a connection to the database server without selecting a database
$conn = getDbConnectionWithoutDatabase();

// Execute the SQL commands from the schema file
if ($conn->multi_query($sql_content)) {
    // If successful, notify the user
    echo "Database initialized successfully.";
} else {
    // If there's an error, display the error message
    echo "Error initializing database: " . $conn->error;
}

// Close the database connection
$conn->close();
?>

x<?php
// Database configuration settings
define('DB_SERVER', 'localhost');  // Typically 'localhost' unless your database is hosted elsewhere
define('DB_USERNAME', 'root');     // Your database username
define('DB_PASSWORD', 'password');         // Your database password
define('DB_NAME', 'notes_managment_system');    // Your database name

// Try connecting to the database
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check the connection
if ($mysqli === false) {
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
?>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $db_host = $_POST["db_host"];
    $db_name = $_POST["db_name"];
    $db_user = $_POST["db_user"];
    $db_password = $_POST["db_password"];

    // Attempt to create the database connection
    $conn = new mysqli($db_host, $db_user, $db_password);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create the database
    $create_db_query = "CREATE DATABASE IF NOT EXISTS $db_name";
    if ($conn->query($create_db_query) === TRUE) {
        echo "Database created successfully!";
    } else {
        echo "Error creating database: " . $conn->error;
    }

    // Close the connection
    $conn->close();
}

?>

<?php
require_once('config.php');

// Create connection
$conn = new mysqli(DB_SERVER, DB_SERVER, DB_PORT, DB_USERNAME, DB_PASSWORD);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database if it doesn't exist
$sqlCreateDb = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conn->query($sqlCreateDb) !== TRUE) {
    die("Error creating database: " . $conn->error);
}

// Switch to the specified database
$conn->select_db(DB_NAME);

// Create the 'columns' and 'tasks' tables if they don't exist
$sqlCreateColumnsTable = "
    CREATE TABLE IF NOT EXISTS columns (
        id INT AUTO_INCREMENT PRIMARY KEY,
        column_id VARCHAR(255) UNIQUE,
        column_name VARCHAR(255)
    )
";
$sqlCreateTasksTable = "
    CREATE TABLE IF NOT EXISTS tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        task_id VARCHAR(255) UNIQUE,
        task_title VARCHAR(255),
        task_content TEXT,
        task_phone VARCHAR(20),
        task_tags VARCHAR(255),
        column_id VARCHAR(255),
        FOREIGN KEY (column_id) REFERENCES columns(column_id)
    )
";
if ($conn->query($sqlCreateColumnsTable) !== TRUE || $conn->query($sqlCreateTasksTable) !== TRUE) {
    die("Error creating table: " . $conn->error);
}

// Get data from AJAX request
$taskId = $_POST['taskId'];
$taskTitle = $_POST['taskTitle'];
$taskContent = $_POST['taskContent'];
$taskPhone = $_POST['taskPhone'];
$taskTags = $_POST['taskTags'];
$taskEmail = $_POST['taskEmail'];
$columnId = $_POST['columnId'];
$columnName = $_POST['columnName'];

// Update or insert the column data into the 'columns' table
$sqlColumn = "INSERT INTO columns (column_id, column_name) VALUES ('$columnId', '$columnName') ON DUPLICATE KEY UPDATE column_name='$columnName'";
$conn->query($sqlColumn);

// Update or insert the task data into the 'tasks' table
$sqlTask = "INSERT INTO tasks (task_id, task_title, task_content, task_phone, task_tags, column_id)
        VALUES ('$taskId', '$taskTitle', '$taskContent', '$taskPhone', '$taskTags', '$columnId')
        ON DUPLICATE KEY UPDATE task_title='$taskTitle', task_content='$taskContent', task_phone='$taskPhone', task_tags='$taskTags'";
if ($conn->query($sqlTask) === TRUE) {
    echo "Task saved successfully";
} else {
    echo "Error: " . $sqlTask . "<br>" . $conn->error;
}

$conn->close();
?>

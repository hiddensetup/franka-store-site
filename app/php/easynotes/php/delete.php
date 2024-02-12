<?php
require_once('config.php');

// Create connection
$conn = new mysqli(DB_SERVER, DB_SERVER, DB_PORT, DB_USERNAME, DB_PASSWORD);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get columnId from POST data
$columnId = $_POST['columnId'];

// Delete tasks associated with the column
$sqlDeleteTasks = "DELETE FROM tasks WHERE column_id = '$columnId'";
if ($conn->query($sqlDeleteTasks) === TRUE) {
    // If tasks are deleted successfully, proceed to delete the column
    $sqlDeleteColumn = "DELETE FROM columns WHERE column_id = '$columnId'";
    if ($conn->query($sqlDeleteColumn) === TRUE) {
        echo "Column deleted successfully";
    } else {
        echo "Error deleting column: " . $conn->error;
    }
} else {
    echo "Error deleting tasks: " . $conn->error;
}

$conn->close();
?>

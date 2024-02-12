<?php
require_once('config.php');

// Create connection
$conn = new mysqli(DB_SERVER, DB_SERVER, DB_PORT, DB_USERNAME, DB_PASSWORD);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Switch to the specified database
$conn->select_db(DB_NAME);

// Fetch columns and associated tasks from the database
$sqlFetchColumns = "SELECT * FROM columns";
$resultColumns = $conn->query($sqlFetchColumns);

if ($resultColumns->num_rows > 0) {
    $data = array();

    while ($column = $resultColumns->fetch_assoc()) {
        $columnData = array(
            "column_id" => $column['column_id'],
            "column_name" => $column['column_name'],
            "tasks" => array()
        );

        // Fetch tasks for the current column
        $sqlFetchTasks = "SELECT * FROM tasks WHERE column_id = '" . $column['column_id'] . "'";
        $resultTasks = $conn->query($sqlFetchTasks);

        while ($task = $resultTasks->fetch_assoc()) {
            $taskData = array(
                "task_id" => $task['task_id'],
                "task_title" => $task['task_title'],
                "task_content" => $task['task_content'],
                "task_phone" => $task['task_phone'],
                "task_tags" => $task['task_tags']
            );

            $columnData["tasks"][] = $taskData;
        }

        $data[] = $columnData;
    }

    // Output data in JSON format
    echo json_encode($data);
} else {
    echo "No data found";
}

$conn->close();
?>

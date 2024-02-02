<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form data and create tables

    $host = $_POST['host'];
    $dbname = $_POST['dbname'];
    $user = $_POST['user'];
    $password = $_POST['password'];

    // Create a PDO instance
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create USERS table
        $pdo->exec("CREATE TABLE IF NOT EXISTS USERS (
                        ID INT AUTO_INCREMENT PRIMARY KEY,
                        NAME VARCHAR(255) NOT NULL,
                        EMAIL VARCHAR(255) NOT NULL,
                        PASSWORD VARCHAR(255) NOT NULL,
                        POSTS JSON
                    )");

        echo "Installation successful!";

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Installer</title>
</head>
<body>
    <h1>Database Installer</h1>
    <form action="" method="post">
        <label for="host">Database Host:</label>
        <input type="text" id="host" name="host" required><br>

        <label for="dbname">Database Name:</label>
        <input type="text" id="dbname" name="dbname" required><br>

        <label for="user">Database User:</label>
        <input type="text" id="user" name="user" required><br>

        <label for="password">Database Password:</label>
        <input type="password" id="password" name="password"><br>

        <button type="submit">Install</button>
    </form>
</body>
</html>

<?php
session_start();

// Include the users file
include 'postore-users.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    $userFound = false;
    foreach ($allowedUsers as $userData) {
        if ($userData['name'] === $input || $userData['email'] === $input) {
            if (password_verify($password, $userData['password'])) {
                $_SESSION['user_id'] = $userData['name'];
                $_SESSION['user_name'] = $userData['name'];
                $response['success'] = true;
                break;
            } else {
                $response['message'] = 'Invalid password';
            }
            $userFound = true;
            break;
        }
    }

    if (!$userFound) {
        $response['message'] = 'User not found';
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>

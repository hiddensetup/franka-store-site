<?php
// Start or resume the session
session_start();

// Include the users file
include 'postore-users.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || !isset($allowedUsers[$_SESSION['user_id']])) {
    // If not logged in or user is not in the allowed users list, return an error
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = json_decode(file_get_contents('php://input'), true);

    // Validate input
    if (isset($postData['postId'])) {
        $postId = htmlspecialchars($postData['postId']);

        // Read existing posts from the JSON file
        $postsFile = '../data/postore-data/posts.json';
        $existingPosts = file_exists($postsFile) ? json_decode(file_get_contents($postsFile), true) : [];

        // Find the post in the array and check if the user is the owner
        $postToDelete = null;
        foreach ($existingPosts as $key => $post) {
            if ($post['id'] === $postId && $post['user_id'] === $_SESSION['user_id']) {
                $postToDelete = $key;
                break;
            }
        }

        if ($postToDelete !== null) {
            // Remove the post from the array
            unset($existingPosts[$postToDelete]);

            // Save the updated posts array back to the JSON file
            file_put_contents($postsFile, json_encode(array_values($existingPosts), JSON_PRETTY_PRINT));

            // Update the user's posts array in users.php by removing the post ID
            $userPosts = &$allowedUsers[$_SESSION['user_id']]['posts'];
            $postIndex = array_search($postId, $userPosts);
            if ($postIndex !== false) {
                unset($userPosts[$postIndex]);
                // Reindex the array to maintain sequential keys
                $userPosts = array_values($userPosts);
            }

            // Save the updated user data back to the users.php file
            $serializedUsers = '<?php $allowedUsers = ' . var_export($allowedUsers, true) . ';';
            file_put_contents('postore-users.php', $serializedUsers);

            // Send a JSON response with success message
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success']);
            exit();
        } else {
            // If the post was not found or the user is not the owner, send an error response
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit();
        }
    }
}

// Send a JSON response with an error message
header('Content-Type: application/json');
echo json_encode(['status' => 'error']);
?>

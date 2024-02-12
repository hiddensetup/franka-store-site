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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process and sanitize the input data
    $postText = htmlspecialchars($_POST['postText']);

    // Process and sanitize the tags input
    $postTags = isset($_POST['postTags']) ? htmlspecialchars($_POST['postTags']) : '';
    $tagsArray = array_map('trim', explode(',', $postTags));

    // Process and upload the image
    $targetDir = "../img/";
// Check if an image is uploaded
if (!empty($_FILES["postImage"]["name"])) {
    $targetFile = $targetDir . basename($_FILES["postImage"]["name"]);

    // Check image type using exif_imagetype
    $imageFileType = exif_imagetype($_FILES["postImage"]["tmp_name"]);

    // Convert the image to WebP format
    if ($imageFileType === IMAGETYPE_JPEG) {
        $img = imagecreatefromjpeg($_FILES["postImage"]["tmp_name"]);
        imagewebp($img, $targetFile, 85);
        imagedestroy($img);
    } elseif ($imageFileType === IMAGETYPE_PNG) {
        $img = imagecreatefrompng($_FILES["postImage"]["tmp_name"]);
        imagewebp($img, $targetFile, 85);
        imagedestroy($img);
    } elseif ($imageFileType === IMAGETYPE_GIF) {
        // Handle GIF images
        copy($_FILES["postImage"]["tmp_name"], $targetFile);
    } else {
        // Handle other image formats
        echo json_encode(['status' => 'error', 'message' => 'Unsupported image format']);
        exit();
    }
} else {
    // If no image is uploaded, use a default image
    $targetFile = $targetDir . "7c5ada3613657fc.gif";
}


    // Process video link
    $videoLink = isset($_POST['videoLink']) ? htmlspecialchars($_POST['videoLink']) : '';

    // Fetch current time from JavaScript (World API)
    $date = isset($_POST['date']) ? htmlspecialchars($_POST['date']) : '';

    // Create an array with post information
    $newPost = [
        'id' => uniqid(), // Add a unique ID for each post
        'user_id' => $_SESSION['user_id'], // Add the user ID to the post
        'text' => $postText,
        'tags' => $tagsArray, // Use the array of tags
        'image' => $targetFile,
        'videoLink' => $videoLink, // Add video link to the post
        'date' => $date, // Change the date format here
    ];

    // Ensure the posts.json file exists
    $postsFile = '../data/postore-data/posts.json';

    // Read existing posts from the JSON file
    $existingPosts = file_exists($postsFile) ? json_decode(file_get_contents($postsFile), true) : [];

    // Add the new post to the array
    array_push($existingPosts, $newPost);

    // Save the updated posts array back to the JSON file
    file_put_contents($postsFile, json_encode($existingPosts, JSON_PRETTY_PRINT));

    // Update the user's posts array in users.php with the new post ID
    $allowedUsers[$_SESSION['user_id']]['posts'][] = $newPost['id'];
    file_put_contents('postore-users.php', '<?php $allowedUsers = ' . var_export($allowedUsers, true) . ';');

    // Send a JSON response with success message and the new post data
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'Post added successfully', 'post' => $newPost]);
    exit();
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $base64Data = $_POST['imageData'];
    $filename = $_POST['filename'];
    $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Data));
    
    $path = '../img/img-converter' . $filename . '.webp';
    
    if (file_put_contents($path, $data)) {
        echo json_encode(['success' => true, 'message' => 'Image saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save image']);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
}
?>

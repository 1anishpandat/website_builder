<?php
// upload.php - Handle file uploads
header('Content-Type: application/json');

if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    $fileName = time() . '_' . basename($_FILES['file']['name']);
    $uploadFile = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        // Save to database
        $pdo = new PDO('mysql:host=localhost;dbname=website_builder', 'username', 'password');
        $stmt = $pdo->prepare('INSERT INTO media (website_id, name, path, type, size) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([1, $_FILES['file']['name'], $uploadFile, $_FILES['file']['type'], $_FILES['file']['size']]);
        
        echo json_encode(['success' => true, 'url' => $uploadFile, 'id' => $pdo->lastInsertId()]);
    } else {
        echo json_encode(['success' => false, 'error' => 'File upload failed']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Upload error']);
}
?>
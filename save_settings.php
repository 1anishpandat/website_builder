<?php
// save_settings.php - Save website settings
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$websiteId = 1; // In a real app, this would come from session/auth

$pdo = new PDO('mysql:host=localhost;dbname=website_builder', 'username', 'password');

foreach ($data as $key => $value) {
    // Check if setting exists
    $stmt = $pdo->prepare('SELECT id FROM website_settings WHERE website_id = ? AND setting_key = ?');
    $stmt->execute([$websiteId, $key]);
    
    if ($stmt->rowCount() > 0) {
        // Update existing setting
        $stmt = $pdo->prepare('UPDATE website_settings SET setting_value = ? WHERE website_id = ? AND setting_key = ?');
        $stmt->execute([is_array($value) ? json_encode($value) : $value, $websiteId, $key]);
    } else {
        // Insert new setting
        $stmt = $pdo->prepare('INSERT INTO website_settings (website_id, setting_key, setting_value) VALUES (?, ?, ?)');
        $stmt->execute([$websiteId, $key, is_array($value) ? json_encode($value) : $value]);
    }
}

echo json_encode(['success' => true]);
?>
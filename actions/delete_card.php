<?php
include_once '../includes/db.php';
$id = $_POST['id'] ?? '';
if (empty($id)) {
    http_response_code(400);
    die("Card ID is required.");
}
    
$stmt = $conn->prepare("DELETE FROM mtg_cards WHERE id = ?");
$stmt->execute([$id]);

foreach (['png', 'large', 'art_crop'] as $size) {
    $path = "../assets/images/$size/$id." . ($size === 'png' ? 'png' : 'jpg');
    if (file_exists($path)) unlink($path);
}

// Redirect back to the index page
header("Location: ../pages/index.php");
exit;
?>
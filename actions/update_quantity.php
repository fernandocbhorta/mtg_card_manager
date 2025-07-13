<?php
include_once '../includes/db.php';
$id = $_POST['id'] ?? '';
if (empty($id)) {
    http_response_code(400);
    die("Card ID is required.");
}
    
$quantity = (int)$_POST['quantity'];
$stmt = $conn->prepare("UPDATE mtg_cards SET quantity = ? WHERE id = ?");
$stmt->execute([$quantity, $id]);

// Redirect back to the index page
header("Location: ../pages/index.php");
exit;
?>
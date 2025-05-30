<?php
require_once 'database.php';

$db = new Database();
$conn = $db->connect();

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("DELETE FROM households WHERE household_id = ?");
    $stmt->execute([$id]);
}

// Redirect back to list
header("Location: create_household.php");
exit;

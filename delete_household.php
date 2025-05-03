<?php
require_once 'database.php';
<<<<<<< HEAD

$db = new Database();
$conn = $db->connect();

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("DELETE FROM households WHERE household_id = ?");
    $stmt->execute([$id]);
}

// Redirect back to list
header("Location: create_household.php");
=======
require_once 'household.php'; // Include the Household class

// Create the database connection
$db = new Database();
$conn = $db->connect();

// Create the Household object
$household = new Household($conn);

// Get the ID from the query parameter
$delete_id = $_GET['delete_id'] ?? null;

if ($delete_id) {
    $result = $household->delete($delete_id);
    if ($result === true) {
        $message = "Household deleted successfully!";
        $messageType = "success"; // green popup
    } else {
        $message = "Error: $result";
        $messageType = "error"; // red popup
    }
}

// Redirect back to the create_household.php page (or the page that displays the list)
header("Location: create_household.php?message=$message&messageType=$messageType");
>>>>>>> branch/galang
exit;

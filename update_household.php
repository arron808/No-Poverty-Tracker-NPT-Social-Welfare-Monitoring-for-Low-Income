<?php
require_once 'database.php';
require_once 'household.php';

$db = new Database();
$conn = $db->connect();
$household = new Household($conn);

// Fetch current data
$id = $_GET['id'] ?? null;
$current = null;
if ($id) {
    $stmt = $conn->prepare("SELECT * FROM households WHERE household_id = ?");
    $stmt->execute([$id]);
    $current = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update logic
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {
    $head_name = $_POST['head_name'];
    $address = $_POST['address'];
    $region = $_POST['region'];
    $registered_date = $_POST['registered_date'];

    $stmt = $conn->prepare("UPDATE households SET head_name = ?, address = ?, region = ?, registered_date = ? WHERE household_id = ?");
    if ($stmt->execute([$head_name, $address, $region, $registered_date, $id])) {
        $message = "✅ Household updated successfully!";
        $current = $_POST; // refresh modal fields
    } else {
        $message = "❌ Update failed.";
    }
}
?>

<!-- Floating modal layout -->
<div style="padding: 20px; max-width: 500px;">
    <h3>Edit Household</h3>
    <?php if ($message): ?><p><?= $message ?></p><?php endif; ?>

    <form method="POST">
        <input type="hidden" name="update" value="1">
        Head Name: <input type="text" name="head_name" value="<?= htmlspecialchars($current['head_name'] ?? '') ?>" required><br><br>
        Address: <textarea name="address"><?= htmlspecialchars($current['address'] ?? '') ?></textarea><br><br>
        Region: <input type="text" name="region" value="<?= htmlspecialchars($current['region'] ?? '') ?>"><br><br>
        Registered Date: <input type="date" name="registered_date" value="<?= htmlspecialchars($current['registered_date'] ?? '') ?>"><br><br>

        <button type="submit">Update</button>
    </form>
</div>

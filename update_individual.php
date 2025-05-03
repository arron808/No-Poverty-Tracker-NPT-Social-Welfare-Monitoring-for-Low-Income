<?php
require_once 'database.php';
require_once 'individual.php';

$db = new Database();
$conn = $db->connect();
$individual = new Individual($conn);

// Fetch current data
$id = $_GET['id'] ?? null;
$current = null;
if ($id) {
    $stmt = $conn->prepare("SELECT * FROM individuals WHERE household_id = ?");
    $stmt->execute([$id]);
    $current = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update logic
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $education_level = $_POST['education_level'];
    $employment_status = $_POST['employment_status'];
    $disability = $_POST['disability'];
    

    $stmt = $conn->prepare("UPDATE individuals SET name = ?, dob = ?, gender = ?, education_level = ?, employment_status = ?, disability = ? WHERE household_id = ?");
    if ($stmt->execute([$name, $dob, $gender, $education_level, $employment_status, $disability, $id])) {
        $message = "✅ Individual updated successfully!";
        $current = $_POST; // refresh modal fields
    } else {
        $message = "❌ Update failed.";
    }
}
?>

<!-- Floating modal layout -->
<div style="padding: 20px; max-width: 500px;">
    <h3>Edit Individual</h3>
    <?php if ($message): ?><p><?= $message ?></p><?php endif; ?>

    <form method="POST">
        <input type="hidden" name="update" value="1">
        Name: <input type="text" name="name" value="<?= htmlspecialchars($current['name'] ?? '') ?>" required><br><br>
        Date of Birth: <textarea name="dob"><?= htmlspecialchars($current['dob'] ?? '') ?></textarea><br><br>
        Gender: <input type="text" name="gender" value="<?= htmlspecialchars($current['gender'] ?? '') ?>"><br><br>
        Education Level: <input type="education_level" name="education_level" value="<?= htmlspecialchars($current['education_level'] ?? '') ?>"><br><br>
        Employment Status: <input type="employment_status" name="employment_status" value="<?= htmlspecialchars($current['employment_status'] ?? '') ?>"><br><br>
        Disability: <input type="disability" name="disability" value="<?= htmlspecialchars($current['disability'] ?? '') ?>"><br><br>
        <button type="submit">Update</button>
    </form>
</div>

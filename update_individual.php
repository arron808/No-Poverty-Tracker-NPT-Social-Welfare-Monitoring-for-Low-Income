<?php
require_once 'database.php';
require_once 'individual.php';
require_once 'auth.php';
$db = new Database();
$conn = $db->connect();
$individual = new Individual($conn);

$id = $_GET['id'] ?? null;
$message = "";
$current = null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM individuals WHERE household_id = ?");
    $stmt->execute([$id]);
    $current = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $education = $_POST['education_level'];
    $employment = $_POST['employment_status'];
    $disability = isset($_POST['disability']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE individuals SET name=?, dob=?, gender=?, education_level=?, employment_status=?, disability=? WHERE household_id=?");
    if ($stmt->execute([$name, $dob, $gender, $education, $employment, $disability, $id])) {
        $message = "✅ Individual updated successfully!";
        $stmt = $conn->prepare("SELECT * FROM individuals WHERE household_id = ?");
        $stmt->execute([$id]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $message = "❌ Update failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Individual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Edit Individual</h3>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Name</label>
            <input class="form-control" name="name" value="<?= htmlspecialchars($current['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Date of Birth</label>
            <input type="date" class="form-control" name="dob" value="<?= htmlspecialchars($current['dob']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Gender</label>
            <select class="form-control" name="gender">
                <option value="Male" <?= $current['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $current['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= $current['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Education Level</label>
            <input class="form-control" name="education_level" value="<?= htmlspecialchars($current['education_level']) ?>">
        </div>
        <div class="mb-3">
            <label>Employment Status</label>
            <input class="form-control" name="employment_status" value="<?= htmlspecialchars($current['employment_status']) ?>">
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="disability" <?= $current['disability'] ? 'checked' : '' ?>>
            <label class="form-check-label">Has Disability?</label>
        </div>
        <button type="submit" class="btn btn-primary">💾 Update</button>
        <a href="create_individual.php" class="btn btn-secondary">← Back</a>
    </form>
</div>
</body>
</html>

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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Household</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if ($message): ?>
                Swal.fire({
                    title: <?= $message === "✅ Household updated successfully!" ? "'Success!'" : "'Error!'" ?>,
                    text: <?= json_encode(str_replace("✅ ", "", str_replace("❌ ", "", $message))) ?>,
                    icon: <?= $message === "✅ Household updated successfully!" ? "'success'" : "'error'" ?>,
                    confirmButtonText: 'OK'
                });
            <?php endif; ?>
        });
    </script>
</head>

<body class="bg-light">
    <div class="container py-4">
        <h2 class="mb-4 text-center">✏️ Edit Household</h2>

        <!-- Back Button -->
        <a href="create_household.php" class="btn btn-secondary mb-4">⬅️ Back</a>

        <!-- Update Form -->
        <form method="POST">
            <input type="hidden" name="update" value="1">

            <div class="mb-3">
                <label for="head_name" class="form-label">Head Name</label>
                <input type="text" class="form-control form-control-lg border-dark shadow-sm" name="head_name" value="<?= htmlspecialchars($current['head_name'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control form-control-lg border-dark shadow-sm" name="address" rows="3"><?= htmlspecialchars($current['address'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label for="region" class="form-label">Region</label>
                <input type="text" class="form-control form-control-lg border-dark shadow-sm" name="region" value="<?= htmlspecialchars($current['region'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="registered_date" class="form-label">Registered Date</label>
                <input type="date" class="form-control form-control-lg border-dark shadow-sm" name="registered_date" value="<?= htmlspecialchars($current['registered_date'] ?? '') ?>">
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">💾 Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
require_once 'database.php';
require_once 'household.php';

$database = new Database();
$conn = $database->connect();
$household = new Household($conn);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $head_name = $_POST['head_name'];
    $address = $_POST['address'];
    $region = $_POST['region'];
    $registered_date = $_POST['registered_date'];

    $result = $household->create($head_name, $address, $region, $registered_date);
    $message = $result === true ? "✅ Household added successfully!" : "❌ Error: $result";
}

$households = $household->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Household List</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS with Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#householdTable').DataTable();
        });
    </script>
</head>

<body class="bg-light">
    <div class="container py-4">
        <h2 class="mb-4 text-center">🏠 No Poverty Tracker - Household Management</h2>

        <?php if ($message): ?>
            <div class="alert alert-info"><?= $message ?></div>
        <?php endif; ?>

        <!-- Form Card -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Add New Household
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="head_name" class="form-label">Head Name</label>
                        <input type="text" class="form-control form-control-lg border-dark shadow-sm" name="head_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control form-control-lg border-dark shadow-sm" name="address" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="region" class="form-label">Region</label>
                        <input type="text" class="form-control form-control-lg border-dark shadow-sm" name="region">
                    </div>
                    <div class="mb-3">
                        <label for="registered_date" class="form-label">Registered Date</label>
                        <input type="date" class="form-control form-control-lg border-dark shadow-sm" name="registered_date">
                    </div>
                    <button type="submit" class="btn btn-success btn-lg">💾 Save</button>
                    <a href="index.php" class="btn btn-success btn-lg">◀️ Back</a>
                </form>
            </div>
        </div>

        <!-- Household Table -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                Household List
            </div>
            <div class="card-body">
                <table id="householdTable" class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Head Name</th>
                            <th>Address</th>
                            <th>Region</th>
                            <th>Registered Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($households as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['household_id']) ?></td>
                                <td><?= htmlspecialchars($row['head_name']) ?></td>
                                <td><?= htmlspecialchars($row['address']) ?></td>
                                <td><?= htmlspecialchars($row['region']) ?></td>
                                <td><?= htmlspecialchars($row['registered_date']) ?></td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="update_household.php?id=<?= $row['household_id'] ?>">
                                        ✏️ Edit
                                    </a>
                                    <a class="btn btn-sm btn-danger" href="delete_household.php?id=<?= $row['household_id'] ?>" onclick="return confirm('Are you sure you want to delete this household?');">
                                        🗑️ Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

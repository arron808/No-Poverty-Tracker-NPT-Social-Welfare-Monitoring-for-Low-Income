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

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Sweet Alert -->
    <script src="sweetalert2.min.js"></script>
    <link rel="stylesheet" href="sweetalert2.min.css">

    <script>
        $(document).ready(function() {
            $('#householdTable').DataTable();
        });
    </script>
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="mb-4">
            <a href="index.php" class="btn btn-secondary">
                ← Back to Home
            </a>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Household List</h2>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createHouseholdModal">
                ➕ Add Household
            </button>
        </div>

        <?php if ($message): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: '<?= strpos($message, "✅") !== false ? "Success!" : "Error!" ?>',
                        text: "<?= strip_tags($message) ?>",
                        icon: '<?= strpos($message, "✅") !== false ? "success" : "error" ?>',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        <?php endif; ?>


        <div class="card shadow p-4 mb-5">
            <table id="householdTable" class="table table-striped">
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
                                <a class="btn btn-sm btn-warning me-1" href="update_household.php?id=<?= $row['household_id'] ?>">Edit</a>
                                <a class="btn btn-sm btn-danger" href="delete_household.php?id=<?= $row['household_id'] ?>" onclick="return confirm('Are you sure you want to delete this household?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Household Modal -->
    <div class="modal fade" id="createHouseholdModal" tabindex="-1" aria-labelledby="createHouseholdModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="createHouseholdModalLabel">Create Household</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="head_name" class="form-label">Head Name</label>
                            <input type="text" class="form-control" name="head_name" id="head_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="region" class="form-label">Region</label>
                            <input type="text" class="form-control" name="region" id="region">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" name="address" id="address" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="registered_date" class="form-label">Registered Date</label>
                            <input type="date" class="form-control" name="registered_date" id="registered_date">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Household</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>
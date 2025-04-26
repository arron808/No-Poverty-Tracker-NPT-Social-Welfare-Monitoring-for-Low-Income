<?php
require_once 'database.php';
require_once 'household.php';

$database = new Database();
$conn = $database->connect();
$household = new Household($conn);

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['create'])) {
        $head_name = $_POST['head_name'];
        $address = $_POST['address'];
        $region = $_POST['region'];
        $registered_date = $_POST['registered_date'];

        $result = $household->create($head_name, $address, $region, $registered_date);
        $message = $result === true ? "✅ Household added successfully!" : "❌ Error: $result";
        $messageType = $result === true ? "success" : "error";

    } elseif (isset($_POST['update'])) {
        $household_id = $_POST['household_id'];
        $head_name = $_POST['head_name'];
        $address = $_POST['address'];
        $region = $_POST['region'];
        $registered_date = $_POST['registered_date'];

        $result = $household->update($household_id, $head_name, $address, $region, $registered_date);
        $message = $result === true ? "✅ Household updated successfully!" : "❌ Error: $result";
        $messageType = $result === true ? "success" : "error";
    }
}

$households = $household->getAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Household List</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert -->
    <script src="sweetalert2.min.js"></script>
    <link rel="stylesheet" href="sweetalert2.min.css">

    <script>
    $(document).ready(function() {
        $('#householdTable').DataTable();

        <?php if ($message): ?>
        Swal.fire({
            icon: '<?= $messageType ?>',
            title: '<?= $message ?>',
            timer: 1500,
            showConfirmButton: false
        });
        <?php endif; ?>

        // Fill edit modal
        $('.btn-edit').on('click', function() {
            var id = $(this).data('id');
            var head_name = $(this).data('head_name');
            var address = $(this).data('address');
            var region = $(this).data('region');
            var registered_date = $(this).data('registered_date');

            $('#editHouseholdModal input[name="household_id"]').val(id);
            $('#editHouseholdModal input[name="head_name"]').val(head_name);
            $('#editHouseholdModal textarea[name="address"]').val(address);
            $('#editHouseholdModal input[name="region"]').val(region);
            $('#editHouseholdModal input[name="registered_date"]').val(registered_date);

            var editModal = new bootstrap.Modal(document.getElementById('editHouseholdModal'));
            editModal.show();
        });

        // Open create modal
        $('#openCreateModal').on('click', function() {
            var createModal = new bootstrap.Modal(document.getElementById('createHouseholdModal'));
            createModal.show();
        });
    });
    </script>

</head>

<body class="p-4 bg-light">

<div class="container bg-white p-4 rounded shadow">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Household Management</h2>
        <div>
            <button id="openCreateModal" class="btn btn-success">➕ Add Household</button>
            <a href="index.php" class="btn btn-secondary ms-2">⬅️ Back to Index</a>
        </div>
    </div>

    <table id="householdTable" class="display table table-striped">
        <thead>
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
                    <button 
                        class="btn btn-sm btn-warning btn-edit"
                        data-id="<?= $row['household_id'] ?>"
                        data-head_name="<?= htmlspecialchars($row['head_name'], ENT_QUOTES) ?>"
                        data-address="<?= htmlspecialchars($row['address'], ENT_QUOTES) ?>"
                        data-region="<?= htmlspecialchars($row['region'], ENT_QUOTES) ?>"
                        data-registered_date="<?= $row['registered_date'] ?>"
                    >Edit</button>

                    <a class="btn btn-sm btn-danger" href="delete_household.php?delete_id=<?= $row['household_id'] ?>" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createHouseholdModal" tabindex="-1" aria-labelledby="createHouseholdModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createHouseholdModalLabel">Create Household</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="create" value="1">
                <div class="mb-3">
                    <label>Head Name:</label>
                    <input type="text" name="head_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Address:</label>
                    <textarea name="address" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label>Region:</label>
                    <input type="text" name="region" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Registered Date:</label>
                    <input type="date" name="registered_date" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Save Household</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editHouseholdModal" tabindex="-1" aria-labelledby="editHouseholdModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editHouseholdModalLabel">Edit Household</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="update" value="1">
                <input type="hidden" name="household_id">
                <div class="mb-3">
                    <label>Head Name:</label>
                    <input type="text" name="head_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Address:</label>
                    <textarea name="address" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label>Region:</label>
                    <input type="text" name="region" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Registered Date:</label>
                    <input type="date" name="registered_date" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update Household</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

</body>

</html>

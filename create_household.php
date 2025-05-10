<?php
require_once 'database.php';
require_once 'household.php';

$database = new Database();
$conn = $database->connect();
$household = new Household($conn);

$message = "";
$result = null;

// Add new household logic
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add'])) {
    $head_name = $_POST['head_name'];
    $address = $_POST['address'];
    $region = $_POST['region'];
    $registered_date = $_POST['registered_date'];

    $result = $household->create($head_name, $address, $region, $registered_date);
    $message = $result === true ? "✅ Household added successfully!" : "❌ Error: $result";
}

// Update household logic (handling form submission for the modal)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {
    $id = $_POST['household_id'];
    $head_name = $_POST['head_name'];
    $address = $_POST['address'];
    $region = $_POST['region'];
    $registered_date = $_POST['registered_date'];

    $stmt = $conn->prepare("UPDATE households SET head_name = ?, address = ?, region = ?, registered_date = ? WHERE household_id = ?");
    if ($stmt->execute([$head_name, $address, $region, $registered_date, $id])) {
        $message = "✅ Household updated successfully!";
    } else {
        $message = "❌ Update failed.";
    }
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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            $('#householdTable').DataTable();
        });

        // Pre-fill the modal with the household data when "Edit" is clicked
        function editHousehold(id) {
            // Find the household from the table
            var row = $('#householdTable').find('tr[data-id="' + id + '"]');
            var head_name = row.find('.head_name').text();
            var address = row.find('.address').text();
            var region = row.find('.region').text();
            var registered_date = row.find('.registered_date').text();

            $('#household_id').val(id);
            $('#edit_head_name').val(head_name);
            $('#edit_address').val(address);
            $('#edit_region').val(region);
            $('#edit_registered_date').val(registered_date);

            $('#editHouseholdModal').modal('show');
        }

        // SweetAlert for success/error message
        $(document).ready(function () {
            <?php if ($message): ?>
                Swal.fire({
                    title: '<?= $message === "✅ Household added successfully!" || $message === "✅ Household updated successfully!" ? "Success!" : "Error!" ?>',
                    text: '<?= $message ?>',
                    icon: '<?= $message === "✅ Household added successfully!" || $message === "✅ Household updated successfully!" ? "success" : "error" ?>',
                    confirmButtonText: 'OK'
                });
            <?php endif; ?>
        });
    </script>
</head>

<body class="bg-light">
    <div class="container py-4">
        <h2 class="mb-4 text-center">🏠 No Poverty Tracker - Household Management</h2>

        <!-- Button to trigger modal -->
        <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addHouseholdModal">+ Add Household</button>

        <!-- Modal Form for Adding Household -->
        <div class="modal fade" id="addHouseholdModal" tabindex="-1" aria-labelledby="addHouseholdModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addHouseholdModalLabel">Add New Household</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <input type="hidden" name="add" value="1">
                            <div class="mb-3">
                                <label for="head_name" class="form-label">Head Name</label>
                                <input type="text" class="form-control form-control-lg" name="head_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control form-control-lg" name="address" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="region" class="form-label">Region</label>
                                <input type="text" class="form-control form-control-lg" name="region">
                            </div>
                            <div class="mb-3">
                                <label for="registered_date" class="form-label">Registered Date</label>
                                <input type="date" class="form-control form-control-lg" name="registered_date">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">💾 Save</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
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
                            <tr data-id="<?= htmlspecialchars($row['household_id']) ?>">
                                <td><?= htmlspecialchars($row['household_id']) ?></td>
                                <td class="head_name"><?= htmlspecialchars($row['head_name']) ?></td>
                                <td class="address"><?= htmlspecialchars($row['address']) ?></td>
                                <td class="region"><?= htmlspecialchars($row['region']) ?></td>
                                <td class="registered_date"><?= htmlspecialchars($row['registered_date']) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="editHousehold(<?= $row['household_id'] ?>)">✏️ Edit</button>
                                    <a class="btn btn-sm btn-danger" href="delete_household.php?id=<?= $row['household_id'] ?>" onclick="return confirm('Are you sure you want to delete this household?');">🗑️ Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form for Editing Household -->
    <div class="modal fade" id="editHouseholdModal" tabindex="-1" aria-labelledby="editHouseholdModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editHouseholdModalLabel">Edit Household</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="update" value="1">
                        <input type="hidden" name="household_id" id="household_id">
                        <div class="mb-3">
                            <label for="edit_head_name" class="form-label">Head Name</label>
                            <input type="text" class="form-control" id="edit_head_name" name="head_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_address" class="form-label">Address</label>
                            <textarea class="form-control" id="edit_address" name="address" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_region" class="form-label">Region</label>
                            <input type="text" class="form-control" id="edit_region" name="region">
                        </div>
                        <div class="mb-3">
                            <label for="edit_registered_date" class="form-label">Registered Date</label>
                            <input type="date" class="form-control" id="edit_registered_date" name="registered_date">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">💾 Update</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

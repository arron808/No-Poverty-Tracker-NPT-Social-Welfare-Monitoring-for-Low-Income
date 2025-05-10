<?php //Create_household.php
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

        // SweetAlert confirmation for delete button
        function confirmDelete(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
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
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete_household.php?id=<?= $row['household_id'] ?>')">🗑️ Delete</button>
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


<?php //update_household.php
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


<?php //household.php

class Household {
    private $conn;

    public function __construct($database) {
        $this->conn = $database;
    }

    public function create($head_name, $address, $region, $registered_date) {
        try {
            $stmt = $this->conn->prepare("CALL CreateHousehold(:head_name, :address, :region, :registered_date)");
            $stmt->execute([
                ':head_name' => $head_name,
                ':address' => $address,
                ':region' => $region,
                ':registered_date' => $registered_date
            ]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function getAll() {
        $stmt = $this->conn->query("CALL GetAllHouseholds()");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $head_name, $address, $region, $registered_date) {
        try {
            $stmt = $this->conn->prepare("CALL UpdateHousehold(:id, :head_name, :address, :region, :registered_date)");
            $stmt->execute([
                ':id' => $id,
                ':head_name' => $head_name,
                ':address' => $address,
                ':region' => $region,
                ':registered_date' => $registered_date
            ]);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM households WHERE household_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}
?>

<?php //index.php ?>
<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>No Poverty Tracker - Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .dashboard-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            color: white;
        }

        .dashboard-card:hover {
            transform: scale(1.03);
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        a.text-decoration-none:hover {
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold">📊 No Poverty Tracker</h1>
        <p class="lead">A simple system to manage households, individuals, and welfare programs</p>
    </div>

    <div class="row justify-content-center g-4">
        <div class="col-md-4">
            <a href="create_household.php" class="text-decoration-none">
                <div class="card dashboard-card bg-primary text-center p-4 border-0 rounded-4">
                    <h2 class="h4 mb-2">🏠 Households</h2>
                    <p class="mb-0">Manage household information</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="create_individual.php" class="text-decoration-none">
                <div class="card dashboard-card bg-success text-center p-4 border-0 rounded-4">
                    <h2 class="h4 mb-2">👤 Individuals</h2>
                    <p class="mb-0">Manage individual data</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="create_program.php" class="text-decoration-none">
                <div class="card dashboard-card bg-danger text-center p-4 border-0 rounded-4">
                    <h2 class="h4 mb-2">🎯 Welfare Programs</h2>
                    <p class="mb-0">Track and update programs</p>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php //database.php

class Database {
    private $host = "localhost";
    private $db_name = "no_poverty_tracker";
    private $username = "root";
    private $password = "";
    public $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name};charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Connection Error: " . $e->getMessage());
        }

        return $this->conn;
    }
}
?>
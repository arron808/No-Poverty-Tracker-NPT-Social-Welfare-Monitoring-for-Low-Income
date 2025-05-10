<?php
require_once 'database.php';
require_once 'individual.php';

$db = new Database();
$conn = $db->connect();
$individual = new Individual($conn);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $household_id = $_POST['household_id'];
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $education = $_POST['education_level'];
    $employment = $_POST['employment_status'];
    $disability = isset($_POST['disability']) ? 1 : 0;

    $result = $individual->create($household_id, $name, $dob, $gender, $education, $employment, $disability);
    $message = $result === true ? "✅ Individual added successfully!" : "❌ Error: $result";
}

$individuals = $individual->getAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Individual List</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS with Bootstrap -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#individualsTable').DataTable();
        });
    </script>
</head>

<body class="bg-light">
    <div class="container py-4">
        <h2 class="mb-4 text-center">🧑 Create Individual</h2>

        <?php if ($message): ?>
            <div class="alert alert-info"><?= $message ?></div>
        <?php endif; ?>

        <!-- Form Card -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Add New Individual
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="household_id" class="form-label">Household ID</label>
                        <input type="number" class="form-control" name="household_id" required style="border: 2px solid #007bff; padding: 10px;">
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required style="border: 2px solid #007bff; padding: 10px;">
                    </div>
                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="dob" style="border: 2px solid #007bff; padding: 10px;">
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-control" name="gender" style="border: 2px solid #007bff; padding: 10px;">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="education_level" class="form-label">Education Level</label>
                        <input type="text" class="form-control" name="education_level" style="border: 2px solid #007bff; padding: 10px;">
                    </div>
                    <div class="mb-3">
                        <label for="employment_status" class="form-label">Employment Status</label>
                        <input type="text" class="form-control" name="employment_status" style="border: 2px solid #007bff; padding: 10px;">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="disability" style="margin-left: 10px;">
                        <label class="form-check-label" for="disability">Disability</label>
                    </div>
                    <button type="submit" class="btn btn-success">💾 Save</button>
                    <a href="index.php" class="btn btn-success">◀️ Back</a>
                </form>
            </div>
        </div>

        <!-- Individual List Table -->
        <h2 class="mb-4">🧑 Individual List</h2>
        <div class="card">
            <div class="card-header bg-dark text-white">
                List of Individuals
            </div>
            <div class="card-body">
                <table id="individualsTable" class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Household ID</th>
                            <th>Name</th>
                            <th>Date of Birth</th>
                            <th>Gender</th>
                            <th>Education Level</th>
                            <th>Employment Status</th>
                            <th>Disability</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($individuals as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['household_id']) ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['dob']) ?></td>
                                <td><?= htmlspecialchars($row['gender']) ?></td>
                                <td><?= htmlspecialchars($row['education_level']) ?></td>
                                <td><?= htmlspecialchars($row['employment_status']) ?></td>
                                <td><?= $row['disability'] ? 'Yes' : 'No' ?></td>
                                <td>
                                    <a class="btn btn-warning btn-sm" href="update_individual.php?id=<?= $row['household_id'] ?>">✏️ Edit</a>
                                    <a class="btn btn-danger btn-sm" href="delete_individual.php?id=<?= $row['household_id'] ?>" onclick="return confirm('Are you sure you want to delete this individual?');">🗑️ Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

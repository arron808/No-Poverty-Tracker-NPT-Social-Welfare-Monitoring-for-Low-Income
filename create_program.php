<?php
session_start();
require_once 'database.php';
require_once 'program.php';

$db = new Database();
$conn = $db->connect();
$program = new Program($conn);

$message = "";
$showModal = false;


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $program_type = $_POST['program_type'];
    $name = $_POST['name']; 
    $description = $_POST['description'];
    $provider = $_POST['provider'];
    $eligibility_criteria = $_POST['eligibility_criteria'];
    $disability = isset($_POST['disability']) ? 1 : 0;

    $result = $program->create($program_type, $name, $description, $provider, $eligibility_criteria); // Include name in the create method
    $message = $result === true ? "✅ Program added successfully!" : "❌ Error: $result";
}

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $showModal = isset($_SESSION['show_modal']);
    unset($_SESSION['message'], $_SESSION['show_modal']);
}

$programs = $program->getAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Individual List</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

  
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#programsTable').DataTable();
        });
    </script>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4 text-center">🎯 Create Welfare Program</h2>

       
        <form method="POST" class="mb-4 p-4 border rounded bg-white shadow-sm">
            <h4 class="text-primary mb-3">Create a New Program</h4>

            <div class="mb-3">
                <label for="programName" class="form-label">Program Type</label>
                <select name="program_type" id="programName" class="form-control" required>
                    <option value="">Select Program</option>
                    <option value="Health Insurance">Health Insurance</option>
                    <option value="Cash Assistance">Cash Assistance</option>
                    <option value="Education Support">Education Support</option>
                    <option value="Disability and Special Needs Assistance">Disability and Special Needs Assistance</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="programNameInput" class="form-label">Name</label>
                <input type="text" name="name" id="programNameInput" class="form-control" placeholder="Enter Program Name" required>
            </div>

            <div class="mb-3">
                <label for="programDescription" class="form-label">Description</label>
                <textarea name="description" id="programDescription" class="form-control" placeholder="Optional..."></textarea>
            </div>

            <div class="mb-3">
                <label for="programProvider" class="form-label">Provider</label>
                <input type="text" name="provider" id="programProvider" class="form-control" placeholder="e.g., DSWD, PhilHealth">
            </div>

            <div class="mb-3">
                <label for="eligibilityCriteria" class="form-label">Eligibility Criteria</label>
                <textarea name="eligibility_criteria" id="eligibilityCriteria" class="form-control" placeholder="e.g., Low-income, PWD, etc."></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100">💾 Save Program</button>
        </form>

        <!-- Programs Table -->
        <h3 class="mb-4 text-center">📋 Welfare Programs List</h3>
        <table id="programsTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Program Type</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Provider</th>
                    <th>Eligibility Criteria</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($programs as $program): ?>
                <tr>
                    <td><?= htmlspecialchars($program['program_id']) ?></td>
                    <td><?= htmlspecialchars($program['name']) ?></td> <!-- Added name column -->
                    <td><?= htmlspecialchars($program['description']) ?></td>
                    <td><?= htmlspecialchars($program['provider']) ?></td>
                    <td><?= htmlspecialchars($program['eligibility_criteria']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 Notification -->
    <?php if ($showModal): ?>
    <script>
        Swal.fire({
            icon: <?= str_starts_with($message, '✅') ? "'success'" : "'error'" ?>,
            title: <?= str_starts_with($message, '✅') ? "'Success'" : "'Error'" ?>,
            text: <?= json_encode(trim($message, "✅❌ ")) ?>,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Okay'
        });
    </script>
    <?php endif; ?>
</body>

</html>

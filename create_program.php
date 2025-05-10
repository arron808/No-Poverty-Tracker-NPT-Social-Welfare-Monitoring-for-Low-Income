<?php
require_once 'database.php';
require_once 'program.php';

$db = new Database();
$conn = $db->connect();
$program = new Program($conn);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $provider = $_POST['provider'];
    $eligibility = $_POST['eligibility_criteria'];

    $result = $program->create($name, $description, $provider, $eligibility);
    $message = $result === true ? "✅ Program added successfully!" : "❌ Error: $result";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Welfare Program</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Simple function to clear the form after submission
        function clearForm() {
            document.getElementById('programForm').reset();
        }
    </script>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4 text-center">🎯 Create Welfare Program</h2>

        <!-- Success or Error Message -->
        <?php if ($message): ?>
            <div class="alert <?= str_starts_with($message, '✅') ? 'alert-success' : 'alert-danger' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <!-- Program Creation Form -->
        <form method="POST" id="programForm" class="mb-4 p-4 border rounded bg-white shadow-sm">
            <h4 class="text-primary mb-3">Create a New Program</h4>

            <div class="mb-3">
                <label for="programName" class="form-label">Program Name</label>
                <input type="text" name="name" id="programName" class="form-control" required placeholder="Enter program name">
            </div>

            <div class="mb-3">
                <label for="programDescription" class="form-label">Description</label>
                <textarea name="description" id="programDescription" class="form-control" placeholder="Enter program description"></textarea>
            </div>

            <div class="mb-3">
                <label for="programProvider" class="form-label">Provider</label>
                <input type="text" name="provider" id="programProvider" class="form-control" placeholder="Enter provider name">
            </div>

            <div class="mb-3">
                <label for="eligibilityCriteria" class="form-label">Eligibility Criteria</label>
                <textarea name="eligibility_criteria" id="eligibilityCriteria" class="form-control" placeholder="Enter eligibility criteria"></textarea>
            </div>

            <button type="submit" class="btn btn-success">💾 Save Program</button>
            <a href="index.php" class="btn btn-success">◀️ Back</a>
        </form>

    </div>
</body>

</html>

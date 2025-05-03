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

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#individualsTable').DataTable();
        });
    </script>
</head>

<body>
<h2>Create Individual</h2>
<form method="POST">
    Household ID: <input type="number" name="household_id" required><br>
    Name: <input type="text" name="name" required><br>
    Date of Birth: <input type="date" name="dob"><br>
    Gender:
    <select name="gender">
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select><br>
    Education Level: <input type="text" name="education_level"><br>
    Employment Status: <input type="text" name="employment_status"><br>
    Disability: <input type="checkbox" name="disability"><br>
    <button type="submit">Save</button>
</form>

    <h2>🧑Individual List</h2>
    <table id="individualsTable" class="display">
        <thead>
            <tr>
                <th>Household ID</th>
                <th>Name</th>
                <th>Date of Birth:</th>
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
                    <td><?= htmlspecialchars($row['disability']) ?></td>
                    <td>
                        <a class="btn btn-edit" href="update_individual.php?id=<?= $row['household_id'] ?>">Edit</a>
                        <a class="btn btn-delete" href="delete_individual.php?id=<?= $row['household_id'] ?>" onclick="return confirm('Are you sure you want to delete this household?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($message): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

</body>

</html>
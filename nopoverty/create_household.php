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

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#householdTable').DataTable();
        });
    </script>
</head>

<body>
    <h2>Create Household</h2>
    <form method="POST">
        Head Name: <input type="text" name="head_name" required><br>
        Address: <textarea name="address"></textarea><br>
        Region: <input type="text" name="region"><br>
        Registered Date: <input type="date" name="registered_date"><br>
        <button type="submit">Save</button>
    </form>

    <h2>🏠 Household List</h2>
    <table id="householdTable" class="display">
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
                        <a class="btn btn-edit" href="update_household.php?id=<?= $row['household_id'] ?>">Edit</a>
                        <a class="btn btn-delete" href="delete_household.php?id=<?= $row['household_id'] ?>" onclick="return confirm('Are you sure you want to delete this household?');">Delete</a>
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
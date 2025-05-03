<?php
session_start();
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

    $_SESSION['message'] = $result === true ? "✅ Program added successfully!" : "❌ Error: $result";

    header("Location: create_program.php");
    exit();
}


if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Welfare Program</title>
</head>
<body>

<h2>Create Welfare Program</h2>

<form method="POST">
    <label>Program Type:</label><br>
    <select name="name" required>
        <option value="">-- Select Program --</option>
        <option value="Health Insurance">Health Insurance</option>
        <option value="Cash Assistance">Cash Assistance</option>
        <option value="Education Support">Education Support</option>
        <option value="Disability and Special Needs Assistance">Disability and Special Needs Assistance</option>
    </select><br><br>

    <label>Description:</label><br>
    <textarea name="description" placeholder="Optional..."></textarea><br>

    <label>Provider:</label><br>
    <input type="text" name="provider" placeholder="e.g., DSWD, PhilHealth"><br>

    <label>Eligibility Criteria:</label><br>
    <textarea name="eligibility_criteria" placeholder="e.g., Low-income, PWD, etc."></textarea><br><br>

    <button type="submit">Save</button>
</form>

<?php if ($message): ?>
    <p><?= $message ?></p>
<?php endif; ?>

</body>
</html>

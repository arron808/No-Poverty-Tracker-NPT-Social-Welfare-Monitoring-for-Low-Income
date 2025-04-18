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

<h2>Create Welfare Program</h2>
<form method="POST">
    Name: <input type="text" name="name" required><br>
    Description: <textarea name="description"></textarea><br>
    Provider: <input type="text" name="provider"><br>
    Eligibility Criteria: <textarea name="eligibility_criteria"></textarea><br>
    <button type="submit">Save</button>
</form>

<?php if ($message): ?>
    <p><?= $message ?></p>
<?php endif; ?>

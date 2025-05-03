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
?>

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

<?php if ($message): ?>
    <p><?= $message ?></p>
<?php endif; ?>

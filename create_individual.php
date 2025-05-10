<?php
require_once 'database.php';
require_once 'individual.php';

$db = new Database();
$conn = $db->connect();
$individual = new Individual($conn);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create'])) {
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

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {
    $id = $_POST['edit_household_id'];
    $name = $_POST['edit_name'];
    $dob = $_POST['edit_dob'];
    $gender = $_POST['edit_gender'];
    $education = $_POST['edit_education_level'];
    $employment = $_POST['edit_employment_status'];
    $disability = isset($_POST['edit_disability']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE individuals SET name = ?, dob = ?, gender = ?, education_level = ?, employment_status = ?, disability = ? WHERE household_id = ?");
    if ($stmt->execute([$name, $dob, $gender, $education, $employment, $disability, $id])) {
        $message = "✅ Individual updated successfully!";
    } else {
        $message = "❌ Update failed.";
    }
}

$individuals = $individual->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Individual List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.js"></script>
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">🧑 Individual List</h2>
        <div>
            <a href="index.php" class="btn btn-secondary me-2">← Back to Home</a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addIndividualModal">Add New Individual</button>
        </div>
    </div>

    <!-- Success Message -->
    <?php if ($message): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?= $message ?>',
                confirmButtonText: 'OK'
            });
        </script>
    <?php endif; ?>

    <!-- Table -->
    <div class="card">
        <div class="card-header bg-dark text-white">List of Individuals</div>
        <div class="card-body">
            <table id="individualsTable" class="table table-striped table-bordered">
                <thead class="table-dark">
                <tr>
                    <th>Household ID</th>
                    <th>Name</th>
                    <th>DOB</th>
                    <th>Gender</th>
                    <th>Education</th>
                    <th>Employment</th>
                    <th>Disability</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($individuals as $row): ?>
                    <tr>
                        <td><?= $row['household_id'] ?></td>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['dob'] ?></td>
                        <td><?= $row['gender'] ?></td>
                        <td><?= $row['education_level'] ?></td>
                        <td><?= $row['employment_status'] ?></td>
                        <td><?= $row['disability'] ? 'Yes' : 'No' ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm editBtn"
                                    data-id="<?= $row['household_id'] ?>"
                                    data-name="<?= htmlspecialchars($row['name']) ?>"
                                    data-dob="<?= $row['dob'] ?>"
                                    data-gender="<?= $row['gender'] ?>"
                                    data-education="<?= htmlspecialchars($row['education_level']) ?>"
                                    data-employment="<?= htmlspecialchars($row['employment_status']) ?>"
                                    data-disability="<?= $row['disability'] ?>">
                                ✏️ Edit
                            </button>
                            <a class="btn btn-danger btn-sm" href="delete_individual.php?id=<?= $row['household_id'] ?>" onclick="return confirm('Delete this individual?')">🗑️ Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addIndividualModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">Add Individual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="create" value="1">
                <div class="mb-3"><label>Household ID</label><input name="household_id" class="form-control" required></div>
                <div class="mb-3"><label>Name</label><input name="name" class="form-control" required></div>
                <div class="mb-3"><label>Date of Birth</label><input type="date" name="dob" class="form-control"></div>
                <div class="mb-3"><label>Gender</label>
                    <select name="gender" class="form-control">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="mb-3"><label>Education Level</label><input name="education_level" class="form-control"></div>
                <div class="mb-3"><label>Employment Status</label><input name="employment_status" class="form-control"></div>
                <div class="form-check"><input type="checkbox" name="disability" class="form-check-input"><label class="form-check-label">Disability</label></div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-success">💾 Save</button></div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editIndividualModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">Edit Individual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="update" value="1">
                <input type="hidden" name="edit_household_id" id="edit_household_id">
                <div class="mb-3"><label>Name</label><input name="edit_name" id="edit_name" class="form-control" required></div>
                <div class="mb-3"><label>Date of Birth</label><input type="date" name="edit_dob" id="edit_dob" class="form-control"></div>
                <div class="mb-3"><label>Gender</label>
                    <select name="edit_gender" id="edit_gender" class="form-control">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="mb-3"><label>Education Level</label><input name="edit_education_level" id="edit_education_level" class="form-control"></div>
                <div class="mb-3"><label>Employment Status</label><input name="edit_employment_status" id="edit_employment_status" class="form-control"></div>
                <div class="form-check"><input type="checkbox" name="edit_disability" id="edit_disability" class="form-check-input"><label class="form-check-label">Disability</label></div>
            </div>
            <div class="modal-footer"><button type="submit" class="btn btn-success">🔁 Update</button></div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#individualsTable').DataTable();

        $('.editBtn').on('click', function () {
            $('#edit_household_id').val($(this).data('id'));
            $('#edit_name').val($(this).data('name'));
            $('#edit_dob').val($(this).data('dob'));
            $('#edit_gender').val($(this).data('gender'));
            $('#edit_education_level').val($(this).data('education'));
            $('#edit_employment_status').val($(this).data('employment'));
            $('#edit_disability').prop('checked', $(this).data('disability') == 1);
            new bootstrap.Modal(document.getElementById('editIndividualModal')).show();
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
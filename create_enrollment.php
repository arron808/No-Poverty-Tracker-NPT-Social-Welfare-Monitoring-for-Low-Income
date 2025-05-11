<?php
session_start();
require_once 'database.php';
require_once 'enrollment.php';
require_once 'program.php';
require_once 'household.php';

$db = new Database();
$conn = $db->connect();

$enrollment = new Enrollment($conn);
$program = new Program($conn);
$household = new Household($conn);

$households = $household->getAll();
$programs = $program->getAll();
$message = "";

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === 'edit') {
        $enrollment->update($_POST['enrollment_id'], $_POST['household_id'], $_POST['program_id']);
        header("Location: create_enrollment.php");
        exit;
    } elseif ($action === 'delete') {
        $enrollment->delete($_POST['enrollment_id']);
        header("Location: create_enrollment.php");
        exit;
    } else {
        // New enrollment
        $household_id = $_POST['household_id'] ?? null;
        $program_id = $_POST['program_id'] ?? null;

        if ($household_id && $program_id) {
            $result = $enrollment->create($household_id, $program_id);
            $message = $result === true ? "✅ Household enrolled successfully!" : "❌ Error: $result";
        } else {
            $message = "❌ Please select both household and program.";
        }
    }
}

$enrollments = $enrollment->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enroll Household</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#enrollmentTable').DataTable();
        });
    </script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4 text-center">🏠 Enroll Household to Program</h2>

    <div class="text-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#enrollModal">➕ Enroll</button>
    </div>

    <!-- Enroll Modal -->
    <div class="modal fade" id="enrollModal" tabindex="-1" aria-labelledby="enrollModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Enroll Household</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="household_id" class="form-label">Select Household</label>
                        <select name="household_id" id="household_id" class="form-select" required>
                            <option value="">-- Choose Household --</option>
                            <?php foreach ($households as $h): ?>
                                <option value="<?= $h['household_id'] ?>"><?= htmlspecialchars($h['head_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="program_id" class="form-label">Select Program</label>
                        <select name="program_id" id="program_id" class="form-select" required>
                            <option value="">-- Choose Program --</option>
                            <?php foreach ($programs as $p): ?>
                                <option value="<?= $p['program_id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">💾 Enroll</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Message -->
    <?php if ($message): ?>
        <div class="alert <?= str_starts_with($message, '✅') ? 'alert-success' : 'alert-danger' ?> text-center">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Enrollment Table -->
    <table class="table table-bordered" id="enrollmentTable">
        <thead>
        <tr>
            <th>Household</th>
            <th>Program</th>
            <th>Date Enrolled</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($enrollments as $enroll): ?>
            <tr>
                <td><?= htmlspecialchars($enroll['head_name']) ?></td>
                <td><?= htmlspecialchars($enroll['program_name']) ?></td>
                <td><?= htmlspecialchars($enroll['date_enrolled']) ?></td>
                <td>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editEnrollmentModal<?= $enroll['enrollment_id'] ?>">✏️ Edit</button>
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteEnrollmentModal<?= $enroll['enrollment_id'] ?>">🗑️ Delete</button>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editEnrollmentModal<?= $enroll['enrollment_id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" class="modal-content">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="enrollment_id" value="<?= $enroll['enrollment_id'] ?>">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Enrollment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Household</label>
                                <select name="household_id" class="form-select" required>
                                    <?php foreach ($households as $hh): ?>
                                        <option value="<?= $hh['household_id'] ?>" <?= $hh['household_id'] == $enroll['household_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($hh['head_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Program</label>
                                <select name="program_id" class="form-select" required>
                                    <?php foreach ($programs as $pg): ?>
                                        <option value="<?= $pg['program_id'] ?>" <?= $pg['program_id'] == $enroll['program_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($pg['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary">Save Changes</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteEnrollmentModal<?= $enroll['enrollment_id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" class="modal-content">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="enrollment_id" value="<?= $enroll['enrollment_id'] ?>">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm Deletion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this enrollment?
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger">Yes, Delete</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

        <?php endforeach; ?>
        </tbody>
    </table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

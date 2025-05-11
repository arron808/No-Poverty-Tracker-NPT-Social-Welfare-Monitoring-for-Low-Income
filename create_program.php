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
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'create') {
            $name = $_POST['name']; 
            $description = $_POST['description'];
            $provider = $_POST['provider'];
            $eligibility_criteria = $_POST['eligibility_criteria'];

            $result = $program->create($name, $description, $provider, $eligibility_criteria);
            $message = $result === true ? "✅ Program added successfully!" : "❌ Error: $result";
            $_SESSION['message'] = $message;
            $_SESSION['show_modal'] = true;
            header("Location: create_program.php");
            exit();
        } elseif ($action === 'update') {
            $id = $_POST['program_id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $provider = $_POST['provider'];
            $eligibility_criteria = $_POST['eligibility_criteria'];

            $result = $program->update($id, $name, $description, $provider, $eligibility_criteria);
            $message = $result === true ? "✅ Program updated successfully!" : "❌ Error: $result";
            $_SESSION['message'] = $message;
            $_SESSION['show_modal'] = true;
            header("Location: create_program.php");
            exit();
        } elseif ($action === 'delete') {
            $id = $_POST['program_id'];
            $result = $program->delete($id);
            $message = $result === true ? "✅ Program deleted successfully!" : "❌ Error: $result";
            $_SESSION['message'] = $message;
            $_SESSION['show_modal'] = true;
            header("Location: create_program.php");
            exit();
        }
    }
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
    <title>Welfare Programs</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4 text-center">🎯 Welfare Programs</h2>
    <div class="text-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">➕ Add Program</button>
    </div>

    <table id="programsTable" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Provider</th>
            <th>Eligibility Criteria</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($programs as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= htmlspecialchars($p['description']) ?></td>
                <td><?= htmlspecialchars($p['provider']) ?></td>
                <td><?= htmlspecialchars($p['eligibility_criteria']) ?></td>
                <td>
                    <button 
                        class="btn btn-sm btn-warning editBtn" 
                        data-id="<?= $p['program_id'] ?>"
                        data-name="<?= htmlspecialchars($p['name']) ?>"
                        data-description="<?= htmlspecialchars($p['description']) ?>"
                        data-provider="<?= htmlspecialchars($p['provider']) ?>"
                        data-eligibility="<?= htmlspecialchars($p['eligibility_criteria']) ?>">
                        ✏️ Edit
                    </button>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="program_id" value="<?= $p['program_id'] ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this program?')">🗑️ Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="modal-header">
                    <h5 class="modal-title">Add Program</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Provider</label>
                        <input type="text" name="provider" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Eligibility Criteria</label>
                        <textarea name="eligibility_criteria" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="program_id" id="editProgramId">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Program</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="editDescription" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Provider</label>
                        <input type="text" name="provider" id="editProvider" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Eligibility Criteria</label>
                        <textarea name="eligibility_criteria" id="editEligibility" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $('#programsTable').DataTable();

        $('.editBtn').click(function () {
            $('#editProgramId').val($(this).data('id'));
            $('#editName').val($(this).data('name'));
            $('#editDescription').val($(this).data('description'));
            $('#editProvider').val($(this).data('provider'));
            $('#editEligibility').val($(this).data('eligibility'));
            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        });
    });
</script>

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

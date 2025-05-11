<?php
session_start();
require_once 'database.php';
require_once 'User.php';
require_once 'auth.php';

$database = new Database();
$conn = $database->connect();
$user = new User($conn);

// Handle form actions (add, edit, delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        $user->createUser($_POST['username'], $_POST['password_hash']);
    } elseif (isset($_POST['edit_user'])) {
        $user->updateUser($_POST['user_id'], $_POST['username'], $_POST['password_hash']);
    } elseif (isset($_POST['delete_user'])) {
        $user->deleteUser($_POST['user_id']);
    }
    header("Location: manage_users.php");
    exit;
}

// Fetch all users
$users = $user->getAllUsers();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">User Accounts</h2>

    <!-- Add User Button -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">➕ Add New User</button>

    <!-- Users Table -->
    <table class="table table-bordered bg-white">
        <thead><tr><th>ID</th><th>Username</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['user_id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td>
                    <!-- Edit Button -->
                    <button 
                        class="btn btn-sm btn-primary me-1"
                        data-bs-toggle="modal"
                        data-bs-target="#editUserModal"
                        data-user-id="<?= $user['user_id'] ?>"
                        data-username="<?= htmlspecialchars($user['username']) ?>"
                    >Edit</button>

                    <!-- Delete Button -->
                    <button 
                        class="btn btn-sm btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteUserModal"
                        data-user-id="<?= $user['user_id'] ?>"
                        data-username="<?= htmlspecialchars($user['username']) ?>"
                    >Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Add New User</h5></div>
            <div class="modal-body">
                <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="modal-footer">
                <button type="submit" name="add_user" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Edit User</h5></div>
            <div class="modal-body">
                <input type="hidden" name="user_id" id="editUserId">
                <input type="text" name="username" id="editUsername" class="form-control mb-3" required>
                <input type="password" name="password" class="form-control" placeholder="New Password (leave blank to keep current)">
            </div>
            <div class="modal-footer">
                <button type="submit" name="edit_user" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Delete User</h5></div>
            <div class="modal-body">
                <input type="hidden" name="user_id" id="deleteUserId">
                <p>Are you sure you want to delete user <strong id="deleteUsername"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="submit" name="delete_user" class="btn btn-danger">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const editModal = document.getElementById('editUserModal');
editModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const userId = button.getAttribute('data-user-id');
    const username = button.getAttribute('data-username');

    editModal.querySelector('#editUserId').value = userId;
    editModal.querySelector('#editUsername').value = username;
});

const deleteModal = document.getElementById('deleteUserModal');
deleteModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const userId = button.getAttribute('data-user-id');
    const username = button.getAttribute('data-username');

    deleteModal.querySelector('#deleteUserId').value = userId;
    deleteModal.querySelector('#deleteUsername').textContent = username;
});
</script>
</body>
</html>

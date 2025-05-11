<?php
include 'auth.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>No Poverty Tracker - Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .dashboard-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            color: white;
        }

        .dashboard-card:hover {
            transform: scale(1.03);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        a.text-decoration-none:hover {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between mb-4">
            <div>
                <h1 class="display-5 fw-bold">📊 No Poverty Tracker</h1>
                <p class="lead">A simple system to manage households, individuals, and welfare programs</p>
            </div>
            <div class="align-self-start">
                <a href="logout.php" class="btn btn-outline-danger">Logout</a>
            </div>
        </div>

        <div class="row justify-content-center g-4">
            <div class="col-md-4">
                <a href="create_household.php" class="text-decoration-none">
                    <div class="card dashboard-card bg-primary text-center p-4 border-0 rounded-4">
                        <h2 class="h4 mb-2">🏠 Households</h2>
                        <p class="mb-0">Manage household information</p>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="create_individual.php" class="text-decoration-none">
                    <div class="card dashboard-card bg-success text-center p-4 border-0 rounded-4">
                        <h2 class="h4 mb-2">👤 Individuals</h2>
                        <p class="mb-0">Manage individual data</p>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="create_program.php" class="text-decoration-none">
                    <div class="card dashboard-card bg-danger text-center p-4 border-0 rounded-4">
                        <h2 class="h4 mb-2">🎯 Welfare Programs</h2>
                        <p class="mb-0">Track and update programs</p>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="manage_users.php" class="text-decoration-none">
                    <div class="card dashboard-card bg-warning text-center p-4 border-0 rounded-4">
                        <h2 class="h4 mb-2">👥 Manage Users</h2>
                        <p class="mb-0">Add or remove system users</p>

                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="create_enrollment.php" class="text-decoration-none">
                    <div class="card dashboard-card bg-warning text-center p-4 border-0 rounded-4">
                        <h2 class="h4 mb-2">📥 Enrollments</h2>
                        <p class="mb-0">Enroll households into programs</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
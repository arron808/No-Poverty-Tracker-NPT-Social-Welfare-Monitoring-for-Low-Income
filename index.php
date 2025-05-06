<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>No Poverty Tracker - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0">No Poverty Tracker</h3>
                    </div>
                    <div class="card-body text-center">
                        <p class="lead">Welcome to the No Poverty Tracker System</p>
                        <div class="d-grid gap-3 col-6 mx-auto mt-4">
                            <a href="create_household.php" class="btn btn-success btn-lg">Households</a>
                            <a href="create_household.php" class="btn btn-success btn-lg">Individuals</a>
                            <a href="create_household.php" class="btn btn-success btn-lg">Welfare Programs</a>

                            <a href="logout.php" class="btn btn-danger btn-lg">Logout</a>
                        </div>
                    </div>
                    <div class="card-footer text-muted text-center">
                        &copy; <?= date('Y') ?> No Poverty Tracker
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

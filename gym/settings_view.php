<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();

}if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
}

require_once 'config/settings_update.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Cook Fitness</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="styles/dash.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <span id="menu-toggle" class="navbar-toggler-icon"></span>
        <a class="navbar-brand" href="#">Cook Fitness</a>
    </div>
</nav>

<div class="sidebar" id="sidebar">
    <button id="close-sidebar" class="btn btn-link text-dark position-absolute top-0 end-0 mt-2 me-2">
        <i class="fas fa-times"></i>
    </button>
    <a href="dashboard_view.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="#"><i class="fas fa-chart-line"></i> Progress Tracker</a>
    <a href="#"><i class="fas fa-file-alt"></i> Reports</a>
    <a href="nutrition_tracker_view.php"><i class="fas fa-utensils"></i> Nutrition Tracker</a>
    <a href="config/challenges.php"><i class="fas fa-trophy"></i> Challenges</a>
    <a href="add_workout.php"><i class="fas fa-dumbbell"></i> Add workout</a>
    <a href="settings_view.php"><i class="fas fa-cog"></i> Settings</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
</div>

<div class="main-content" id="main-content">
    <h1 class="mb-4">Settings</h1>

    <div class="row">
        <div class="col-md-6">
            <h2>Profile Information</h2>
            <form action="settings_view.php" method="POST">
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="mb-3">
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($user['date_of_birth']) ?? ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-control" id="gender" name="gender">
                        <option value="Male" <?php echo $user['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo $user['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo $user['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="height" class="form-label">Height (cm)</label>
                    <input type="number" step="0.1" class="form-control" id="height" name="height" value="<?php echo htmlspecialchars($user['height']) ?? ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="weight" class="form-label">Weight (kg)</label>
                    <input type="number" step="0.1" class="form-control" id="weight" name="weight" value="<?php echo htmlspecialchars($latest_measurement['weight']) ?? ''; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
        <div class="col-md-6">
            <h2>Fitness Metrics</h2>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">BMI</h5>
                    <p class="card-text">
                        <?php if (isset($latest_measurement['weight']) && isset($user['height']) && $user['height'] > 0) {
                            echo calculateBMI($latest_measurement['weight'], $user['height']);
                        } else {
                            echo 'Not available';
                        } ?></p>
                    <p class="card-text"><small class="text-muted">Based on your latest weight and height</small></p>
                </div>
            </div>
            <h2>Goals</h2>
            <?php if (empty($goals)): ?>
                <p>You haven't set any goals yet.</p>
            <?php else: ?>
                <?php foreach ($goals as $goal): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($goal['goal_type']); ?> Goal</h5>
                            <p class="card-text">Target: <?php echo htmlspecialchars($goal['target_value']); ?></p>
                            <p class="card-text">Current: <?php echo htmlspecialchars($goal['current_value']); ?></p>
                            <p class="card-text"><small class="text-muted">Deadline: <?php echo htmlspecialchars($goal['deadline']); ?></small></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <a href="config/add_goal.php" class="btn btn-primary">Add New Goal</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar toggle
    document.getElementById('menu-toggle').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('main-content').classList.toggle('active');
    });

    // Close sidebar
    document.getElementById('close-sidebar').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('main-content').classList.toggle('active');
    });
</script>
</body>
</html>
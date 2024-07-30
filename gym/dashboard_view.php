<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/dash.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cook Fitness - Dashboard</title>
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
    <a href="nutrition_tracker_view.php"><i class="fas fa-utensils"></i> Nutrition Tracker</a>
    <a href="add_workout.php"><i class="fas fa-dumbbell"></i> Add workout</a>
    <a href="settings_view.php"><i class="fas fa-cog"></i> Settings</a>
    <a href="config/logout.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
</div>

<div class="main-content" id="main-content">
    <h1 class="mb-4">Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>

    <div class="row">
        <div class="col-md-3">
            <div class="stat-card">
                <h5>Workouts Completed</h5>
                <h2><?php echo $stats['workouts_completed']; ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h5>Total Duration (mins)</h5>
                <h2><?php echo $stats['total_duration'] ? round($stats['total_duration']) : 0; ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h5>Avg. Duration (mins)</h5>
                <h2><?php echo $stats['avg_duration'] ? round($stats['avg_duration']) : 0; ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h5>Longest Streak</h5>
                <h2><?php echo $stats['longest_streak']; ?></h2>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="chart-container">
                <h5>Calories Burned</h5>
                <canvas id="caloriesChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <h5>Monthly Check-ins</h5>
                <canvas id="checkinsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    // Calories Burned Chart
    var caloriesCtx = document.getElementById('caloriesChart').getContext('2d');
    var caloriesChart = new Chart(caloriesCtx, {
        type: 'bar',
        data: {
            labels: ['Total Calories Burned'],
            datasets: [{
                label: 'Calories',
                data: [<?php echo $stats['total_calories']; ?>],
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Monthly Check-ins Chart
    var checkinsCtx = document.getElementById('checkinsChart').getContext('2d');
    var checkinsChart = new Chart(checkinsCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_column($monthly_checkins, 'date')); ?>,
            datasets: [{
                label: 'Check-ins',
                data: <?php echo json_encode(array_column($monthly_checkins, 'count')); ?>,
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
</body>
</html>

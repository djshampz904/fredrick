<?php
// At the beginning of nutrition_tracker_view.php
if (!isset($user) || !isset($nutrition_logs) || !isset($avg_intake)) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cook Fitness - Nutrition Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
        }

        .sidebar {
            background-color: #e9ecef;
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: -250px;
            padding-top: 60px;
            transition: 0.3s;
            z-index: 1000;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar a {
            color: #212529;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
        }

        .sidebar a:hover {
            background-color: #dee2e6;
        }

        .main-content {
            padding: 20px;
            transition: 0.3s;
        }

        .main-content.active {
            margin-left: 250px;
        }

        .stat-card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .chart-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .navbar {
            background-color: #f8f9fa !important;
        }

        #menu-toggle {
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .main-content.active {
                margin-left: 0;
            }
        }
    </style>
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
    <a href="config/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="#"><i class="fas fa-chart-line"></i> Progress Tracker</a>
    <a href="#"><i class="fas fa-file-alt"></i> Reports</a>
    <a href="nutrition_tracker.php"><i class="fas fa-utensils"></i> Nutrition Tracker</a>
    <a href="#"><i class="fas fa-trophy"></i> Challenges</a>
    <a href="config/workout_plans.php"><i class="fas fa-dumbbell"></i> Add workout</a>
    <a href="#"><i class="fas fa-cog"></i> Settings</a>
</div>

<div class="main-content" id="main-content">
    <h1 class="mb-4">Nutrition Tracker</h1>

    <div class="row">
        <div class="col-md-3">
            <div class="stat-card">
                <h5>Avg. Daily Calories</h5>
                <h2><?php echo round($avg_intake['avg_calories']); ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h5>Avg. Daily Protein (g)</h5>
                <h2><?php echo round($avg_intake['avg_protein'], 1); ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h5>Avg. Daily Carbs (g)</h5>
                <h2><?php echo round($avg_intake['avg_carbs'], 1); ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h5>Avg. Daily Fats (g)</h5>
                <h2><?php echo round($avg_intake['avg_fats'], 1); ?></h2>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="chart-container">
                <h5>Daily Calorie Intake (Last 7 Days)</h5>
                <canvas id="calorieChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <h5>Macronutrient Breakdown</h5>
                <canvas id="macroChart"></canvas>
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

    // Calorie Intake Chart
    var calorieCtx = document.getElementById('calorieChart').getContext('2d');
    var calorieChart = new Chart(calorieCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_column($nutrition_logs, 'date')); ?>,
            datasets: [{
                label: 'Calories',
                data: <?php echo json_encode(array_column($nutrition_logs, 'total_calories')); ?>,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
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

    // Macronutrient Breakdown Chart
    var macroCtx = document.getElementById('macroChart').getContext('2d');
    var macroChart = new Chart(macroCtx, {
        type: 'pie',
        data: {
            labels: ['Protein', 'Carbs', 'Fats'],
            datasets: [{
                data: [
                    <?php echo $avg_intake['avg_protein']; ?>,
                    <?php echo $avg_intake['avg_carbs']; ?>,
                    <?php echo $avg_intake['avg_fats']; ?>
                ],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ]
            }]
        }
    });
</script>
</body>
</html>
